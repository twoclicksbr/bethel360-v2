# Bethel360° — Documentação do Projeto

## O que é o Bethel360°

**Bethel** vem do hebraico בֵּית אֵל (Beit El), que significa **"Casa de Deus"**. Bethel360° é a **visão completa da Casa de Deus** — uma plataforma completa para gestão de igrejas que conecta tecnologia e espiritualidade.

## Stack Técnica

- **Backend:** Laravel 11 (PHP 8.3+)
- **Banco de Dados:** PostgreSQL (1 banco por tenant)
- **Multi-tenancy:** Stancl/Tenancy
- **Auth:** Laravel Sanctum (API tokens)
- **Cache:** Redis
- **Queue:** Redis
- **API Docs:** L5-Swagger

## Arquitetura

### Multi-Tenancy (Isolamento Total)

- **1 banco PostgreSQL por tenant** (não é schema separado — é banco inteiro)
- Stancl/Tenancy resolve pelo domínio: `{tenant.slug}.bethel360.com.br`
- White-label: igreja pode usar domínio próprio (ex: `igrejadacidade.net.br`)
- Migrations do tenant rodam no banco de cada igreja
- Tabelas centrais (tenants, domains) ficam no banco principal

### Hierarquia da Plataforma

```
Tenant (Igreja)
└── Campus (Sede ou Filial)
    └── Ministério (30 Semanas, Louvor, GDC, etc.)
        └── Grupo (Sala de aula, célula, turma)
```

**Regra absoluta:** Se tem atividade, tem ministério. Se tem ministério, pode ter grupo.

### API-First

Tudo é **API REST JSON**. Ninguém acessa banco direto:
- Painel web (admin/líder)
- App mobile (membro/líder)
- Sites institucionais (pages públicas e restritas)
- Google Workspace (Meet, Drive, Calendar)
- Integrações externas (WhatsApp, Asaas)

## Estrutura do Código

### BaseModel

Todo model herda de `BaseModel`:
```php
class BaseModel extends Model
{
    use SoftDeletes, HasAudit;
}
```

### BaseController

CRUD genérico completo com:
- Paginação (`?page=N&per_page=N`)
- Filtros (`?filter[campo]=valor`)
- Busca (`?search=texto`)
- Ordenação (`?sort=campo` ou `?sort=-campo`)
- Includes (`?include=relacao1,relacao2`)
- Soft delete e restore

Controller filho só define:
```php
protected $model = Ministry::class;
protected $request = MinistryRequest::class;
protected $resource = MinistryResource::class;
protected $searchableFields = ['name'];
```

### Submódulos Polimórficos

Tabelas reutilizáveis via polimorfismo:
- **Addresses** — endereços de Campus, Ministry, Group, Person
- **Contacts** — emails, telefones, WhatsApp
- **Documents** — CPF, RG, CNH, etc.
- **Files** — arquivos de qualquer entidade
- **Notes** — anotações/descrições

Qualquer model usa `morphMany` sem FK direta.

### Traits (Features)

| Trait | Onde usa | O que faz |
|-------|----------|-----------|
| HasAudit | BaseModel | Log automático de alterações |
| HasFeatures | Ministry, Group | Gênero, idade, capacidade, pré-requisitos |
| HasMembers | Ministry, Group | Vínculos ministry_persons/group_persons |
| HasPresence | Group, Event | Registro de presença |
| HasTimeline | Person | Histórico cronológico |
| HasQrCode | Person | Código 6 dígitos (ID formatado) |
| HasAchievements | Person | Conquistas espirituais |
| HasFamily | Person | Vínculos familiares |
| HasEvents | Ministry, Group | Agenda de eventos |
| HasFinances | Person, Ministry | Movimentações financeiras |
| HasGroups | Ministry | Relacionamento ministry → groups |
| HasAddresses | Campus, Ministry, Group, Person | Endereços polimórficos |
| HasContacts | Campus, Ministry, Group, Person, AuthorizedPickup | Contatos polimórficos |
| HasDocuments | Person, AuthorizedPickup | Documentos polimórficos |
| HasFiles | Campus, Ministry, Group | Arquivos polimórficos |
| HasNotes | Campus, Ministry, Group, Event | Notas polimórficas |

## Conceitos Fundamentais

### Pessoas e Vínculos

- **Person pertence ao Tenant** (cadastro único)
- **User** existe só para autenticação (se Person tem User → cadastro completo, se não → convidado)
- **E-mail é a chave** que conecta tudo
- Timeline começa quando líder registra mínimo (nome, email, gênero, WhatsApp) OU quando pessoa se cadastra

### Features (Regras dos Planetas)

Cada ministério/grupo define suas regras através de **Features**:
- Gênero (masculino, feminino, misto)
- Faixa etária (0-11, 12-14, 15-17, 18+)
- Modalidade (presencial, online)
- Ciclo (aberto, fechado)
- Mobilidade (livre, fechada)
- Perfil (aberto ou restrito)
- Campus (aceita outros campus ou não)
- Capacidade (limite de participantes)
- Pré-requisitos (precisa ter concluído X antes)
- Localização (endereço — essencial para GDC/células)

**Regra de herança:** Grupo herda features do ministério. GroupFeature só adiciona restrições extras, nunca contradiz.

### Status por Vínculo

Cada participação (ministry_persons, group_persons) tem status independente:
- **Ativo** — participando normalmente
- **Inativo** — afastado, saiu ou banido
- **Pendente** — aguardando alocação/aprovação
- **Concluído** — terminou o ciclo/curso (conquista espiritual)

### QR Code

- Identidade digital universal da pessoa
- **Formato:** ID formatado em 6 dígitos (id 42 → "000042")
- **Usos:** Check-in em culto, presença em grupo pequeno, identificação na recepção, retirada de crianças

### Presença

**Automática** — ninguém marca manualmente:
- **Grupo grande (cultos):** QR Code no telão → pessoa escaneia
- **Grupo pequeno (células):** Líder escaneia QR Code da pessoa
- **Online (Meet):** Sistema sabe tempo de permanência
- **Fallback:** Manual por email/código se necessário

**Dois tipos de presença:**
- **Participante** — está ali pra receber (features se aplicam)
- **Servindo** — está ali escalado por outro ministério (presença cai no ministério dele)

### Segurança de Crianças

**Protocolo inegociável:**
- Lista permanente de **pessoas autorizadas** cadastrada pelo responsável
- Na saída: QR Code valida se está autorizado
- **Não autorizado:** bloqueio total + alerta ao responsável + liderança acionada
- Protege casos de guarda judicial

### Conquistas Espirituais

Não são só registros — são **chaves que abrem portas:**
- Concluir 30 Semanas → habilita ser confidente
- Concluir Rota do Conhecimento → habilita liderar célula
- Concluir nível 1 → libera nível 2

Título montado automaticamente com base em `is_achievement` das features.

### Família (Rede Social Interna)

- Vínculos construídos por **solicitação e aceite** (como rede social)
- Cônjuge, filho, pai, mãe, irmão, tio, avó
- Ministério pode criar vínculos automaticamente (ex: criança no kids)

## Middleware Stack

```
Request → TenantResolve (Stancl)
       → AuthenticateApi (Sanctum)
       → ResolvePersonFromUser
       → CheckPermission
       → Controller
```

## Versionamento

**Versiona** (contrato externo):
- Routes → `/api/v2/...`
- Controllers → `V2/`
- Resources → `V2/`

**Não versiona** (interno):
- Models, Migrations, Services, Traits, Policies, Jobs

## Padrões de Response

```json
// Sucesso
{ "data": { }, "status": 200 }

// Erro de validação
{ "message": "Dados inválidos", "errors": { "email": ["E-mail obrigatório"] }, "status": 422 }

// Não autenticado
{ "message": "Não autenticado", "status": 401 }

// Não autorizado
{ "message": "Sem permissão", "status": 403 }

// Não encontrado
{ "message": "Registro não encontrado", "status": 404 }

// Erro interno
{ "message": "Erro interno do servidor", "status": 500 }
```

## Cache Strategy (Redis)

### Cacheia (muda pouco):
- Features do tenant
- Ministérios (estrutura, features, config)
- Grupos (estrutura, features, membros, capacidade)
- Hierarquia campus → ministério → grupo
- Permissões da Person
- Lista de autorizados por criança
- Pages (pública e restrita)

### Não cacheia (muda sempre):
- Presença (escrita constante, vai pra fila)
- Timeline (leitura sob demanda)
- Finanças (transacional)

**Invalidação:** Por evento — quando algo muda, flush no cache relacionado.

## Queue Strategy (Redis)

Filas separadas:
- **default:** jobs gerais
- **presence:** ProcessPresenceBatchJob (prioridade alta)
- **payments:** ProcessFinanceJob

## Rate Limiting

- Global (visitante): 60 req/min por IP
- Autenticado: 120 req/min por token
- Presença: SEM limite (pico 30 Semanas)
- Auth/Login: 5 tentativas/min
- Finanças: 10 req/min

## Soft Deletes

Todos os models usam `SoftDeletes` via `BaseModel`. **Nenhum dado é apagado definitivamente.**

Exceção: `AuditLog` nunca sofre delete (permanente).

## Observers e Events

### Observers (automático ao salvar):
- **PersonObserver:** Criou pessoa → gera QR Code (6 dígitos)
- **MinistryPersonObserver:** Mudou status → atualiza timeline
- **GroupPersonObserver:** Status = concluído → dispara PersonCompleted, gera Achievement
- **PresenceObserver:** Registrou presença → dispara PresenceRegistered
- **AchievementObserver:** Conquista criada → checa portas que abriu

### Events:
- PersonEnrolled
- PersonCompleted
- PresenceRegistered
- ChildPickupAttempt
- FamilyLinkRequested
- ServiceAssigned
- FinanceReceived

## Services (Lógica de Negócio)

- **EnrollmentService:** Valida features, capacidade, pré-requisitos. Inscreve em ministry/group.
- **PresenceService:** QR Code, manual, batch, Meet.
- **TimelineService:** Monta timeline cronológica da pessoa.
- **ChildSafetyService:** Valida authorized_pickups, bloqueia não autorizados.
- **FamilyLinkService:** Solicitação e aceite de vínculos.
- **FinanceService:** Registra entradas/saídas. Placeholder Asaas.
- **QrCodeService:** Gera e valida QR Code (6 dígitos).
- **ServiceRequestService:** Convocação de ministérios, escalas, aceite/recusa.

## Policies (Authorization)

Baseada na **Person** (não User):
- **CampusPolicy:** view (qualquer autenticado), create/update/delete (painel_restricao)
- **MinistryPolicy:** view (qualquer autenticado), manage (líder ou admin)
- **GroupPolicy:** view (respeitando is_confidential), manage (líder do grupo/ministério)
- **PersonPolicy:** view (própria pessoa ou líder), update (própria pessoa ou admin)
- **PresencePolicy:** register (líder do grupo ou autenticado em culto)

## Módulos da Plataforma

1. **Central de Vidas** — Cadastro completo, timeline, QR Code
2. **Família** — Rede social interna, vínculos familiares, segurança de crianças
3. **Conquistas Espirituais** — Marcos da jornada, habilitações
4. **Agenda Viva** — Calendário dinâmico (não existe agenda sem ministério)
5. **Gestão do Reino** — Financeiro (dízimo, oferta, oferta farol)
6. **Servir Bem** — Escalas e voluntariado (aceite/recusa)
7. **Painel da Liderança** — Dashboard estratégico com indicadores

## Templates de Ministério

| Template | Comportamento | Plano |
|----------|--------------|-------|
| **Padrão** | Operação ministerial padrão | Base |
| **Loja Virtual** | E-commerce | Premium |
| **Distribuição** | Hub de conteúdo | Premium |
| **ERP** | Gestão administrativa | Premium |

Inicialmente apenas **Padrão** está disponível. Demais são roadmap futuro.

## Integrações (Futuro)

- WhatsApp
- Google Meet (salas criadas pelo Bethel360°)
- Google Drive (até 2TB)
- Google Calendar (push de notificações)
- Google Chat
- Banco Asaas (PIX, boletos, cartão)

## Regras de Ouro

1. **Se tem atividade, tem ministério. Se tem ministério, pode ter grupo.**
2. **E-mail é a chave** que conecta tudo.
3. **Grupo herda features do ministério** — nunca contradiz.
4. **QR Code é o ID formatado** em 6 dígitos.
5. **Person pertence ao Tenant**, não ao campus.
6. **Soft delete em tudo** (exceto AuditLog).
7. **Presença automática** — líder não marca, sistema registra.
8. **Segurança de crianças é inegociável** — protocolo de autorização.
9. **Timeline começa no primeiro contato** — mesmo antes do cadastro completo.
10. **Conquistas são chaves** — habilitam novos passos.

## Comandos Importantes

```bash
# Rodar migrations do tenant
php artisan tenants:migrate

# Rodar seeders do tenant
php artisan tenants:seed

# Criar novo tenant
php artisan tenants:create

# Limpar cache
php artisan cache:clear

# Rodar filas
php artisan queue:work --queue=presence,payments,default
```

## Estrutura de Pastas

```
app/
├── Http/
│   ├── Controllers/V2/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/V2/
├── Models/
├── Features/ (Traits)
├── Services/
├── Policies/
├── Observers/
├── Events/
├── Listeners/
├── Jobs/
└── Exceptions/
```

## Ordem das Migrations

### Submódulos Base (00a-00q)
modules → statuses → roles → genders → type_addresses → addresses → type_contacts → contacts → type_documents → documents → files → notes → relationships → presence_methods → finance_types → finance_categories → payment_methods

### Core (01-17)
features → campuses → ministries → groups → ministry_features + group_features → people → users → ministry_persons → group_persons → family_links → authorized_pickups → events → presences → achievements → finances → service_requests + service_assignments → audit_logs

## Observações Importantes

- **Notes:** Migration separada para FK `person_id` (criada depois de `people`)
- **User:** NÃO herda BaseModel (herda Authenticatable), mas usa SoftDeletes
- **AuditLog:** SEM updated_at, SEM soft_deletes (permanente)
- **Addresses:** Latitude/Longitude para geolocalização (futuro)
- **Documents:** Campo `expires_at` para validade (CNH, certidões)

---

## Roteiro de Implementação (3 Etapas)

### ETAPA 1 — FUNDAÇÃO (Manhã)

**Objetivo:** Setup inicial do projeto, migrations, models, auth e seeders.

#### 1. Setup Inicial
- Laravel 11, PHP 8.3+
- Instalar e configurar: `stancl/tenancy` (multi-tenant com banco separado por tenant), `laravel/sanctum` (auth API), `darkaonline/l5-swagger`
- Configurar Redis como driver de cache e queue
- Configurar PostgreSQL como banco de dados
- Configurar Stancl para resolver tenant por domínio/subdomínio (`{slug}.bethel360.com.br`)

#### 2. BaseModel
Criar `app/Models/BaseModel.php` que todo model vai herdar:
- use SoftDeletes
- use HasAudit (trait que será criada)

#### 3. Migrations (tenant — rodam no banco de cada igreja)
Criar na seguinte ordem, todas com `soft_deletes` e `timestamps` (exceto `audit_logs`):

**Submódulos Base (00a-00q):**
- 00a - modules
- 00b - statuses
- 00c - roles
- 00d - genders
- 00e - type_addresses
- 00f - addresses (polimórfica)
- 00g - type_contacts
- 00h - contacts (polimórfica)
- 00i - type_documents
- 00j - documents (polimórfica)
- 00k - files (polimórfica)
- 00l - notes (polimórfica)
- 00m - relationships
- 00n - presence_methods
- 00o - finance_types
- 00p - finance_categories
- 00q - payment_methods

**Core (01-17):**
- 01 - features
- 02 - campuses
- 03 - ministries
- 04 - groups
- 05a - ministry_features (pivot)
- 05b - group_features (pivot)
- 06 - people
- 07 - users
- 08 - ministry_persons (pivot)
- 09 - group_persons (pivot)
- 10 - family_links
- 11 - authorized_pickups
- 12 - events
- 13 - presences
- 14 - achievements
- 15 - finances
- 16a - service_requests
- 16b - service_assignments
- 17 - audit_logs

**IMPORTANTE:** A tabela `notes` referencia `people` (`person_id`), mas `people` é criada depois. Usar migration separada para adicionar FK de `notes.person_id` APÓS criação de `people`.

#### 4. Models
Criar todos os models herdando de `BaseModel`, com `fillable`, `casts`, e relacionamentos conforme migrations.

**User:** NÃO herda BaseModel (herda Authenticatable), mas usa SoftDeletes.

#### 5. Auth (Sanctum)
- **LoginController:** recebe email+senha, valida, retorna token. Se User não existe mas Person existe com email em contacts (convidado), retorna erro orientando cadastro.
- **RegisterController:** recebe dados completos. Se email existe em contacts de Person (convidado), vincula User à Person existente. Se não, cria Person + User. ID da pessoa vira código 6 dígitos (id 42 → 000042).
- **LogoutController:** revoga token atual.
- **ForgotPasswordController:** placeholder com TODO.

#### 6. Middleware
- **AuthenticateApi:** valida token Sanctum (`auth:sanctum`)
- **ResolvePersonFromUser:** após auth, resolve User->person e injeta no request como `$request->person`
- **CheckPermission:** placeholder (inicialmente retorna true — implementação real virá com Policies)
- **EnsureActiveStatus:** verifica se person não está com todos vínculos inativos

#### 7. Seeders (TenantSeeder)
Criar TenantSeeder que roda ao criar tenant:
- ModuleSeeder
- StatusSeeder (por módulo)
- RoleSeeder (por módulo)
- GenderSeeder
- FeatureSeeder
- RelationshipSeeder
- PresenceMethodSeeder
- FinanceTypeSeeder
- FinanceCategorySeeder
- PaymentMethodSeeder
- TypeAddressSeeder
- TypeContactSeeder
- TypeDocumentSeeder

**Validação:** Garantir que tudo funciona: migrate, seed, login, logout, register.

---

### ETAPA 2 — CORE (Tarde)

**Objetivo:** Implementar traits, BaseController, Resources, Controllers e Services (lógica de negócio).

#### 1. Traits (app/Features/)

**HasAudit:**
- Usa model events (created, updated, deleted) para registrar automaticamente em `audit_logs`
- Captura person_id, action, model, model_id, changes (before/after), ip, response

**HasFeatures:**
- `features()`: retorna MinistryFeatures ou GroupFeatures
- `matchesProfile(Person)`: cruza features com perfil da pessoa (gênero, idade)
- `availableCapacity()`: retorna vagas restantes
- **REGRA:** Grupo herda features do ministério. GroupFeature só adiciona restrições extras, nunca contradiz.

**HasMembers:**
- `ministryPersons()` ou `groupPersons()`
- `activeMembersCount()`
- `enroll(Person, role_id, status_id)`
- `updateMemberStatus(Person, status_id)`

**HasPresence:**
- `presences()`
- `registerPresence(Person, role_id, presence_method_id)`

**HasTimeline:**
- `timeline()`: merge ordenado de presenças, conquistas, vínculos, finanças

**HasQrCode:**
- `getQrCode()`: retorna código 6 dígitos (ID formatado)
- `static resolveFromQrCode(string)`: encontra pessoa pelo código

**HasAchievements:**
- `achievements()`
- `hasCompleted(ministry_id ou group_id)`
- `grantAchievement(ministry_id, group_id)`
- Título montado automaticamente com base em `is_achievement`

**HasFamily:**
- `familyLinks()`: vínculos aceitos
- `pendingFamilyRequests()`
- `authorizedPickups()`: para crianças

**HasEvents:**
- `events()`
- `upcomingEvents()`

**HasFinances:**
- `finances()`
- `financesByType(finance_type_id)`

**HasGroups:** (para Ministry)
- `groups()`

**Polimórficos:**
- HasAddresses, HasContacts, HasDocuments, HasFiles, HasNotes

#### 2. BaseController (app/Http/Controllers/V2/BaseController.php)

CRUD genérico completo:

**index():**
- Paginação (`?page=N&per_page=N`, default 15)
- Filtros (`?filter[campo]=valor`)
- Busca (`?search=texto` — name, email)
- Ordenação (`?sort=campo` ou `?sort=-campo`)
- Includes (`?include=relacao1,relacao2`)
- Retorna via Resource

**show($id):**
- Suporta `?include=`
- Retorna via Resource

**store():**
- Valida via Request class
- Cria registro
- Retorna via Resource, status 201

**update($id):**
- Valida via Request class
- Atualiza registro
- Retorna via Resource

**destroy($id):**
- Soft delete
- Retorna status 204

**restore($id):**
- Restaura soft delete
- Retorna via Resource

Controller filho só define:
```php
protected $model = Ministry::class;
protected $request = MinistryRequest::class;
protected $resource = MinistryResource::class;
protected $searchableFields = ['name'];
```

#### 3. Resources (app/Http/Resources/V2/)

Criar Resources para cada model:
- CampusResource
- MinistryResource
- GroupResource
- PersonResource
- PersonGuestResource (campos mínimos)
- PresenceResource
- TimelineResource
- AchievementResource
- FinanceResource
- EventResource
- FamilyLinkResource
- ServiceRequestResource
- ServiceAssignmentResource

Formato padrão: `{ "data": { ... }, "meta": { ... } }`

#### 4. Controllers (app/Http/Controllers/V2/)

**CRUD simples (herdam BaseController):**
- CampusController
- MinistryController
- GroupController
- PersonController
- FeatureController
- EventController

**Com ações específicas:**
- **EnrollmentController:** `enrollMinistry()`, `enrollGroup()` — usa EnrollmentService
- **PresenceController:** `register()` (QR/manual), `registerBatch()` (massa)
- **TimelineController:** `index(Person)` — retorna timeline
- **FamilyLinkController:** `store()` (solicita vínculo), `respond()` (aceita/recusa)
- **ChildSafetyController:** `validate()` — valida se pode retirar criança
- **ServiceRequestController:** `store()` (convocação), `respond()` (aceita/recusa)
- **ServiceAssignmentController:** `store()` (aloca voluntário), `respond()` (aceita/recusa)
- **FinanceController:** `store()` (registra movimentação), `history(Person)` (histórico)

**Aninhados:**
- CampusMinistryController
- MinistryGroupController
- GroupPersonController

#### 5. Rotas (routes/api.php)

Configurar todas as rotas:
- Auth público: login, register, forgot-password
- CRUD autenticado: campus, ministries, groups, people, events, features
- Aninhadas: campus.ministries, ministries.groups, groups.people
- Inscrição, Presença, Timeline, Família, Segurança, Servir Bem, Financeiro, Restore

Middleware stack: `auth:sanctum`, `resolve.person`, `check.permission`

#### 6. Services

**EnrollmentService:**
- `enrollInMinistry()`: verifica features, pré-requisitos, cria MinistryPerson
- `enrollInGroup()`: verifica features + herança, capacidade, pré-requisitos, cria GroupPerson

**PresenceService:**
- `registerByQrCode()`: resolve pessoa por código 6 dígitos
- `registerManual()`: fallback
- `registerBatch()`: dispatch ProcessPresenceBatchJob

**ChildSafetyService:**
- `validatePickup()`: checa AuthorizedPickup. Se não autorizado, dispara ChildPickupAttempt e retorna false

**TimelineService:**
- `build(Person)`: merge ordenado de presenças, conquistas, vínculos, finanças

**FamilyLinkService:**
- `request()`: cria FamilyLink pendente
- `respond()`: atualiza status

**FinanceService:**
- `create()`: cria Finance pendente (placeholder Asaas)

**QrCodeService:**
- `generateForPerson()`: código 6 dígitos
- `resolve()`: retorna Person

**ServiceRequestService:**
- `createRequest()`: cria ServiceRequest pendente
- `respondRequest()`: aceita/recusa
- `assignPerson()`: cria ServiceAssignment
- `respondAssignment()`: voluntário aceita/recusa

**Validação:** Garantir que todos endpoints funcionam: CRUD, enrollment, presença, timeline, family links, child safety, servir bem, financeiro.

---

### ETAPA 3 — INFRAESTRUTURA (Noite)

**Objetivo:** Implementar Policies, Observers, Events, Jobs, Cache, Rate Limiting e Testes.

#### 1. Policies (app/Policies/)

Authorization baseada na **Person** (não User):

**CampusPolicy:**
- view: qualquer pessoa autenticada do tenant
- create/update/delete: person com permissão `painel_restricao`

**MinistryPolicy:**
- view: qualquer pessoa autenticada
- create/update/delete: permissão `painel_restricao` OU líder do ministério
- viewReports: líder do ministério ou superior

**GroupPolicy:**
- view: qualquer autenticado (respeita `is_confidential` — se true, só membros)
- create/update/delete: líder do ministério pai
- manageMembers: líder do grupo ou ministério
- viewReports: líder do grupo ou superior

**PersonPolicy:**
- view: própria pessoa OU líder de grupo/ministério onde ela participa
- update: própria pessoa OU admin
- delete: admin apenas
- viewTimeline: própria pessoa OU líder (respeitando `is_achievement`)

**PresencePolicy:**
- register: líder do grupo (grupo pequeno) OU qualquer autenticado (culto QR telão)

Registrar policies no `AuthServiceProvider`. Usar `$this->authorizeResource()` nos controllers.

Criar tabela `permissions` e `person_permissions` (pivot). Middleware `CheckPermission` consulta essa tabela.

#### 2. Observers (app/Observers/)

**PersonObserver:**
- created: gera código QR 6 dígitos a partir do ID

**MinistryPersonObserver:**
- created: adiciona entrada na timeline
- updated: se status = concluído, dispara PersonCompleted e gera Achievement (se `is_achievement` = true)

**GroupPersonObserver:**
- created: adiciona entrada na timeline
- updated: se status = concluído, dispara PersonCompleted e gera Achievement conforme regras

**PresenceObserver:**
- created: dispara PresenceRegistered event

**AchievementObserver:**
- created: verifica se conquista desbloqueia algo (checa pré-requisitos de outros grupos)

Registrar observers no `EventServiceProvider` (ou boot do model).

#### 3. Events e Listeners

**Events:**
- PersonEnrolled(Person, Ministry ou Group)
- PersonCompleted(Person, Ministry, Group nullable)
- PresenceRegistered(Presence)
- ChildPickupAttempt(Person child, Person requester nullable, string name, bool authorized)
- FamilyLinkRequested(FamilyLink)
- ServiceAssigned(ServiceAssignment)
- FinanceReceived(Finance)

**Listeners:**
- **UpdateTimeline:** escuta PersonEnrolled, PersonCompleted, PresenceRegistered, FinanceReceived (placeholder — timeline é query)
- **CheckPrerequisites:** escuta PersonCompleted. Verifica se habilita algo novo.
- **NotifyLeader:** escuta PersonEnrolled, FamilyLinkRequested, ServiceAssigned (placeholder notificação)
- **AlertChildSafety:** escuta ChildPickupAttempt. Se não autorizado, loga crítico, cria audit_log especial.

Registrar no `EventServiceProvider`.

#### 4. Jobs (app/Jobs/)

**ProcessPresenceBatchJob:**
- Recebe array de QR codes + event_id
- Resolve cada código para Person
- Registra presença
- Tolerante a falhas

**GenerateQrCodeJob:**
- Recebe person_id
- Gera código 6 dígitos
- Placeholder para imagem QR

**ProcessFinanceJob:**
- Placeholder callback Asaas
- Recebe external_id, atualiza status

**GenerateReportJob:**
- Placeholder — parâmetros (ministry_id, date_range)

**CleanupExpiredTokensJob:**
- Limpa personal_access_tokens expirados (30+ dias)

Configurar filas em `config/queue.php`:
- default: jobs gerais
- presence: ProcessPresenceBatchJob (prioridade alta)
- payments: ProcessFinanceJob

#### 5. Cache Strategy

**Cachear (Redis):**
- Features do tenant (tag: `tenant_features`)
- Hierarquia campus>ministry>group (tag: `hierarchy`)
- ministry_features (tag: `ministry.{id}`)
- group_features (tag: `group.{id}`)
- authorized_pickups por criança (tag: `child_safety.{child_id}`)
- Permissões da person (tag: `person_permissions.{id}`)

**TTL:**
- Features/hierarquia: 1 hora
- Permissões: 30 min
- Child safety: 15 min

**Invalidação:** Observers invalidam cache quando dados mudam (ex: MinistryFeature salva → flush tag do ministry).

Adicionar cache nos Services e Controllers (especialmente EnrollmentService e ChildSafetyService).

#### 6. Rate Limiting

Configurar em `bootstrap/app.php` (Laravel 11):
- `global`: 60/min por IP
- `authenticated`: 120/min por token
- `auth-login`: 5/min por IP
- `finances`: 10/min por token
- Presença: SEM rate limit

#### 7. CORS

Configurar `config/cors.php`:
- allowed_origins: `['*.bethel360.com.br']` + domínios white-label (dinâmico via tenant)
- allowed_methods: `['GET', 'POST', 'PUT', 'PATCH', 'DELETE']`
- allowed_headers: `['Content-Type', 'Authorization', 'Accept']`
- supports_credentials: `true`

#### 8. Error Handling

Configurar `bootstrap/app.php` (Laravel 11):
- ModelNotFoundException → 404
- AuthenticationException → 401
- AuthorizationException → 403
- ValidationException → 422
- Throwable → 500
- Sempre JSON, nunca HTML

#### 9. Seeders Completos

Garantir que TenantSeeder está completo e rodando todos seeders.

#### 10. Testes

**Feature Tests:**
- Auth/LoginTest
- Auth/RegisterTest
- ChildSafety/PickupAuthorizationTest
- Enrollment/EnrollmentTest
- Presence/PresenceTest
- ServiceRequest/ServiceRequestTest
- CRUD básicos (Campus, Ministry, Group, Person)

**Unit Tests:**
- EnrollmentServiceTest
- ChildSafetyServiceTest
- PresenceServiceTest
- ServiceRequestServiceTest

Usar `RefreshDatabase`. Configurar tenant de teste no setup.

**Validação:** Rodar todos testes. Se falhar, corrigir até todos ficarem verdes.

---

**Última atualização:** 2026-02-09
