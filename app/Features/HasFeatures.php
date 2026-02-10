<?php

namespace App\Features;

use App\Models\Person;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * HasFeatures Trait
 *
 * Motor de matching de features (regras dos planetas).
 * Valida gênero, idade, pré-requisitos, capacidade.
 * REGRA ABSOLUTA: Grupo herda features do ministério, só adiciona restrições.
 * Usado por: Ministry, Group
 */
trait HasFeatures
{
    /**
     * Get all features for the model.
     */
    public function features(): BelongsToMany
    {
        // Detecta se é Ministry ou Group dinamicamente
        if ($this instanceof Group) {
            return $this->belongsToMany(
                \App\Models\Feature::class,
                'group_features',
                'group_id',
                'feature_id'
            )->withPivot([
                'value',
                'min_value',
                'max_value',
                'prerequisite_ministry_id',
                'prerequisite_group_id',
            ])->withTimestamps();
        }

        return $this->belongsToMany(
            \App\Models\Feature::class,
            'ministry_features',
            'ministry_id',
            'feature_id'
        )->withPivot([
            'value',
            'min_value',
            'max_value',
            'prerequisite_ministry_id',
            'prerequisite_group_id',
        ])->withTimestamps();
    }

    /**
     * Check if person's profile matches the entity's features.
     *
     * @param Person $person
     * @return bool
     */
    public function matchesProfile(Person $person): bool
    {
        $features = $this->features;

        // Se é um Group, merge com features do Ministry (herança)
        if ($this instanceof Group) {
            $ministryFeatures = $this->ministry->features;

            // Merge: group features sobrescrevem ministry features
            $featuresById = $ministryFeatures->keyBy('id');

            foreach ($features as $groupFeature) {
                $featuresById[$groupFeature->id] = $groupFeature;
            }

            $features = $featuresById->values();
        }

        foreach ($features as $feature) {
            // Validar gênero
            if ($feature->slug === 'genero') {
                $genderValue = $feature->pivot->value;

                if ($genderValue === 'misto') {
                    continue; // Aceita todos
                }

                if ($person->gender && $person->gender->slug !== $genderValue) {
                    return false;
                }
            }

            // Validar idade
            if ($feature->slug === 'faixa-etaria') {
                $minAge = $feature->pivot->min_value;
                $maxAge = $feature->pivot->max_value;

                if ($person->birth_date) {
                    $age = $person->birth_date->age;

                    if ($minAge !== null && $age < $minAge) {
                        return false;
                    }

                    if ($maxAge !== null && $age > $maxAge) {
                        return false;
                    }
                }
            }

            // TODO ETAPA 3: Validar outros features (modalidade, ciclo, perfil, etc.)
        }

        return true;
    }

    /**
     * Calculate available capacity.
     *
     * @return int|null (null = unlimited)
     */
    public function availableCapacity(): ?int
    {
        $capacityFeature = $this->features()
            ->where('slug', 'capacidade')
            ->first();

        if (!$capacityFeature) {
            return null; // Sem limite
        }

        $maxCapacity = $capacityFeature->pivot->max_value;

        if ($maxCapacity === null) {
            return null; // Sem limite
        }

        // Conta membros ativos
        $activeMembersCount = $this->activeMembersCount();

        return max(0, $maxCapacity - $activeMembersCount);
    }

    /**
     * Check if entity has reached maximum capacity.
     *
     * @return bool
     */
    public function isFull(): bool
    {
        $available = $this->availableCapacity();

        return $available !== null && $available <= 0;
    }

    /**
     * Get prerequisites (ministry or group that must be completed).
     *
     * @return array ['ministry_ids' => [...], 'group_ids' => [...]]
     */
    public function getPrerequisites(): array
    {
        $prerequisites = $this->features()
            ->where(function ($query) {
                $query->whereNotNull('prerequisite_ministry_id')
                    ->orWhereNotNull('prerequisite_group_id');
            })
            ->get();

        return [
            'ministry_ids' => $prerequisites->pluck('pivot.prerequisite_ministry_id')->filter()->unique()->values()->toArray(),
            'group_ids' => $prerequisites->pluck('pivot.prerequisite_group_id')->filter()->unique()->values()->toArray(),
        ];
    }
}
