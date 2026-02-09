# Seeders do Bethel360°

## Estrutura

Este diretório contém todos os seeders do projeto Bethel360°. Os seeders são organizados de forma modular e executados automaticamente quando um novo tenant é criado.

## TenantSeeder (Principal)

O `TenantSeeder` é o seeder principal que chama todos os seeders individuais na ordem correta. Ele é executado automaticamente quando um tenant é criado.

### Ordem de Execução

1. **ModuleSeeder** - Módulos base do sistema (7 módulos)
2. **StatusSeeder** - Status por módulo (Ativo, Inativo, Pendente, Concluído, etc.)
3. **RoleSeeder** - Roles por módulo (Participante, Líder, Colaborador, etc.)
4. **GenderSeeder** - Gêneros (Masculino, Feminino, Outro)
5. **FeatureSeeder** - Features disponíveis (14 features)
6. **RelationshipSeeder** - Tipos de relacionamento familiar (23 tipos)
7. **PresenceMethodSeeder** - Métodos de registro de presença (6 métodos)
8. **FinanceTypeSeeder** - Tipos de transação financeira (Entrada, Saída)
9. **FinanceCategorySeeder** - Categorias financeiras por tipo (24 categorias)
10. **PaymentMethodSeeder** - Métodos de pagamento (7 métodos)
11. **TypeAddressSeeder** - Tipos de endereço (6 tipos)
12. **TypeContactSeeder** - Tipos de contato (10 tipos)
13. **TypeDocumentSeeder** - Tipos de documento (10 tipos)

## Comandos

### Seedar tenant existente

```bash
php artisan tenants:seed --tenant=<tenant_id>
```

### Seedar todos os tenants

```bash
php artisan tenants:seed
```

### Seedar tenant específico com seeder específico

```bash
php artisan tenants:seed --tenant=<tenant_id> --class=ModuleSeeder
```

### Criar novo tenant (executa seeders automaticamente)

```bash
php artisan tenants:create
```

## Módulos

Os seguintes módulos são criados automaticamente:

1. **Central de Vidas** - Cadastro completo, timeline, QR Code
2. **Família** - Rede social interna, vínculos familiares, segurança de crianças
3. **Conquistas Espirituais** - Marcos da jornada, habilitações
4. **Agenda Viva** - Calendário dinâmico
5. **Gestão do Reino** - Financeiro (dízimo, oferta, oferta farol)
6. **Servir Bem** - Escalas e voluntariado (aceite/recusa)
7. **Painel da Liderança** - Dashboard estratégico com indicadores

## Features

As seguintes features são criadas para personalização de ministérios e grupos:

- **gender** - Restrição de gênero (masculino, feminino, misto)
- **age-range** - Faixa etária permitida (0-11, 12-14, 15-17, 18+, all)
- **modality** - Modalidade (presencial, online, híbrido)
- **cycle** - Tipo de ciclo (aberto, fechado)
- **mobility** - Mobilidade de participantes (livre, fechada)
- **profile** - Perfil (aberto, restrito)
- **campus-restriction** - Aceita participantes de outros campus
- **capacity** - Limite de participantes
- **prerequisite** - Ministério/grupo que deve ser concluído antes
- **location** - Endereço do ministério/grupo
- **is-confidential** - Grupo confidencial (só membros veem)
- **completion** - Gera conquista ao concluir
- **duration-weeks** - Duração em semanas do ciclo
- **minimum-attendance** - Percentual mínimo de presença para conclusão

## Métodos de Presença

- **qrcode-telao** - Pessoa escaneia QR Code exibido no telão (cultos)
- **qrcode-scanner** - Líder escaneia QR Code da pessoa (grupos pequenos)
- **manual** - Registro manual por email ou código (fallback)
- **google-meet** - Detecção automática por tempo de permanência no Meet
- **nfc** - Aproximação de tag NFC (futuro)
- **biometria** - Reconhecimento biométrico (futuro)

## Categorias Financeiras

### Entradas (Receitas)
- Dízimo
- Oferta
- Oferta Farol
- Doação
- Primícia
- Evento
- Loja

### Saídas (Despesas)
- Aluguel
- Energia
- Água
- Internet
- Salários
- Manutenção
- Material
- Transporte
- Marketing
- Missões
- Ações Sociais
- Impostos
- Equipamento
- Evento
- Diversos

## Observações Importantes

1. **Slugs são únicos** - Todos os registros usam slug como chave única (kebab-case)
2. **Soft Deletes** - Nenhum dado é realmente deletado (is_active controla visibilidade)
3. **Timestamps** - Todos os seeders incluem created_at e updated_at
4. **Tenant Isolado** - Cada tenant tem seu próprio banco de dados com dados isolados
5. **Idempotência** - Os seeders podem ser executados múltiplas vezes sem duplicar dados (se necessário, adicione verificações de existência)

## Desenvolvimento

Ao adicionar novos seeders:

1. Crie o arquivo do seeder em `database/seeders/`
2. Adicione-o ao `TenantSeeder` na ordem correta
3. Verifique dependências de chave estrangeira
4. Use slugs kebab-case para identificadores únicos
5. Inclua timestamps em todos os registros
6. Documente no README.md

## Exemplo de Uso

```php
// Criar um novo tenant
$tenant = Tenant::create([
    'name' => 'Igreja Exemplo',
    'slug' => 'igreja-exemplo',
]);

// Criar domínio
$tenant->domains()->create([
    'domain' => 'igreja-exemplo.bethel360.com.br',
]);

// Os seeders são executados automaticamente
// Verifique os dados criados:
tenancy()->initialize($tenant);
$modules = Module::all();
$features = Feature::all();
```
