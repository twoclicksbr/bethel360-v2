# Bethel360° — Arquitetura da API

---

## Stack

- **Laravel 11** (PHP 8.3+)
- **Multi-tenancy:** Stancl/Tenancy (banco separado por tenant)
- **Auth:** Laravel Sanctum
- **Cache:** Redis
- **Queue:** Redis
- **API Docs:** L5-Swagger

---

## Multi-Tenancy (Stancl/Tenancy)

Um banco de dados por tenant. O Stancl resolve pelo domínio/subdomínio:

```
{tenant.slug}.bethel360.com.br → conecta ao banco do tenant
dominio-proprio.com.br → resolve via domain table → conecta ao banco
```

Migrations do tenant rodam no banco de cada igreja. Tabelas centrais (tenants, domains) ficam no banco principal.

---

## Estrutura de Pastas

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── V2/
│   │   │   ├── BaseController.php
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── LogoutController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── ForgotPasswordController.php
│   │   │   ├── CampusController.php
│   │   │   ├── MinistryController.php
│   │   │   ├── GroupController.php
│   │   │   ├── PersonController.php
│   │   │   ├── PresenceController.php
│   │   │   ├── EnrollmentController.php
│   │   │   ├── TimelineController.php
│   │   │   ├── AchievementController.php
│   │   │   ├── FinanceController.php
│   │   │   ├── FamilyLinkController.php
│   │   │   ├── ChildSafetyController.php
│   │   │   ├── ServiceRequestController.php
│   │   │   ├── ServiceAssignmentController.php
│   │   │   └── EventController.php
│   │   └── ...
│   ├── Middleware/
│   │   ├── AuthenticateApi.php
│   │   ├── ResolvePersonFromUser.php
│   │   ├── CheckPermission.php
│   │   └── EnsureActiveStatus.php
│   ├── Requests/
│   │   ├── Campus/
│   │   │   ├── StoreCampusRequest.php
│   │   │   └── UpdateCampusRequest.php
│   │   ├── Ministry/
│   │   ├── Group/
│   │   ├── Person/
│   │   │   ├── StorePersonRequest.php
│   │   │   ├── UpdatePersonRequest.php
│   │   │   └── StoreGuestRequest.php
│   │   ├── Enrollment/
│   │   ├── Presence/
│   │   ├── Finance/
│   │   ├── Family/
│   │   └── Auth/
│   └── Resources/
│       └── V2/
│           ├── CampusResource.php
│           ├── MinistryResource.php
│           ├── GroupResource.php
│           ├── PersonResource.php
│           ├── PersonGuestResource.php
│           ├── PresenceResource.php
│           ├── TimelineResource.php
│           ├── AchievementResource.php
│           ├── FinanceResource.php
│           ├── EventResource.php
│           ├── FamilyLinkResource.php
│           ├── ServiceRequestResource.php
│           └── ServiceAssignmentResource.php
├── Models/
│   ├── BaseModel.php
│   ├── Module.php
│   ├── Status.php
│   ├── Role.php
│   ├── Gender.php
│   ├── TypeAddress.php
│   ├── Address.php
│   ├── TypeContact.php
│   ├── Contact.php
│   ├── TypeDocument.php
│   ├── Document.php
│   ├── File.php
│   ├── Note.php
│   ├── Relationship.php
│   ├── Feature.php
│   ├── Campus.php
│   ├── Ministry.php
│   ├── Group.php
│   ├── MinistryFeature.php
│   ├── GroupFeature.php
│   ├── Person.php
│   ├── User.php
│   ├── MinistryPerson.php
│   ├── GroupPerson.php
│   ├── FamilyLink.php
│   ├── AuthorizedPickup.php
│   ├── Event.php
│   ├── PresenceMethod.php
│   ├── Presence.php
│   ├── Achievement.php
│   ├── FinanceType.php
│   ├── FinanceCategory.php
│   ├── PaymentMethod.php
│   ├── Finance.php
│   ├── ServiceRequest.php
│   ├── ServiceAssignment.php
│   └── AuditLog.php
├── Features/ (Traits)
│   ├── HasFeatures.php
│   ├── HasPresence.php
│   ├── HasMembers.php
│   ├── HasTimeline.php
│   ├── HasQrCode.php
│   ├── HasAchievements.php
│   ├── HasFamily.php
│   ├── HasEvents.php
│   ├── HasFinances.php
│   ├── HasAddresses.php
│   ├── HasContacts.php
│   ├── HasDocuments.php
│   ├── HasFiles.php
│   ├── HasNotes.php
│   └── HasAudit.php
├── Services/
│   ├── EnrollmentService.php
│   ├── PresenceService.php
│   ├── TimelineService.php
│   ├── ChildSafetyService.php
│   ├── FamilyLinkService.php
│   ├── FinanceService.php
│   ├── QrCodeService.php
│   └── ServiceRequestService.php
├── Policies/
│   ├── CampusPolicy.php
│   ├── MinistryPolicy.php
│   ├── GroupPolicy.php
│   ├── PersonPolicy.php
│   └── PresencePolicy.php
├── Observers/
│   ├── PersonObserver.php
│   ├── MinistryPersonObserver.php
│   ├── GroupPersonObserver.php
│   ├── PresenceObserver.php
│   └── AchievementObserver.php
├── Events/
│   ├── PersonEnrolled.php
│   ├── PersonCompleted.php
│   ├── PresenceRegistered.php
│   ├── ChildPickupAttempt.php
│   ├── FamilyLinkRequested.php
│   ├── ServiceAssigned.php
│   └── FinanceReceived.php
├── Listeners/
│   ├── UpdateTimeline.php
│   ├── CheckPrerequisites.php
│   ├── NotifyLeader.php
│   ├── AlertChildSafety.php
│   └── SyncGoogleCalendar.php
├── Jobs/
│   ├── GenerateReportJob.php
│   ├── ProcessFinanceJob.php
│   ├── SyncGoogleCalendarJob.php
│   ├── SyncGoogleMeetJob.php
│   ├── GenerateQrCodeJob.php
│   ├── ProcessPresenceBatchJob.php
│   └── CleanupExpiredTokensJob.php
└── Exceptions/
    └── Handler.php
```

---

## BaseModel

```php
class BaseModel extends Model
{
    use SoftDeletes, HasAudit;
}
```

Todo model herda do BaseModel. Soft delete e auditoria automática em tudo.

---

## BaseController

```php
class BaseController extends Controller
{
    protected $model;
    protected $request;
    protected $resource;

    public function index()     // listar com filtros, busca, paginação, includes
    public function show($id)   // detalhar um registro
    public function store()     // criar
    public function update($id) // atualizar
    public function destroy($id)// soft delete
    public function restore($id)// restaurar
}
```

Funcionalidades automáticas do index():

```
?filter[campo]=valor    → filtra
?search=texto           → busca por nome/email
?sort=campo             → ordena (- pra desc)
?include=relação        → carrega relacionamentos
?page=N&per_page=N      → paginação
```

Controller filho só define:

```php
class MinistryController extends BaseController
{
    protected $model = Ministry::class;
    protected $request = MinistryRequest::class;
    protected $resource = MinistryResource::class;
}
```

---

## Submódulos Polimórficos

Tabelas reutilizáveis que servem pra qualquer entidade via polimorfismo do Laravel:

### Addresses
```php
class Campus extends BaseModel
{
    use HasAddresses;
    // $campus->addresses
}
```

### Contacts
```php
class Person extends BaseModel
{
    use HasContacts;
    // $person->contacts (email, whatsapp, telefone...)
}
```

### Documents
```php
class Person extends BaseModel
{
    use HasDocuments;
    // $person->documents (CPF, RG...)
}
```

### Files
```php
class Ministry extends BaseModel
{
    use HasFiles;
    // $ministry->files
}
```

### Notes
```php
class Ministry extends BaseModel
{
    use HasNotes;
    // $ministry->notes (substitui campo description)
}
```

Qualquer model usa `morphMany` sem precisar de FK direta.

---

## Models e Relacionamentos

### Module
```
- id, name, slug, module_name, endpoint_name, order, timestamps, soft_deletes
- has many Statuses
- has many Roles
```

### Status
```
- id, module_id (fk), name, slug, order, timestamps, soft_deletes
- belongs to Module
```

### Role
```
- id, module_id (fk), name, slug, order, timestamps, soft_deletes
- belongs to Module
```

### Gender
```
- id, name, slug, order, timestamps, soft_deletes
```

### TypeAddress
```
- id, name, slug, order, timestamps, soft_deletes
```

### Address (polimórfica)
```
- id, type_address_id (fk), addressable_type, addressable_id, zip_code, street, number (nullable), complement (nullable), neighborhood (nullable), city, state, country (default 'Brasil'), latitude (decimal nullable), longitude (decimal nullable), timestamps, soft_deletes
- belongs to TypeAddress
- morphTo addressable
```

### TypeContact
```
- id, name, slug, order, timestamps, soft_deletes
```

### Contact (polimórfica)
```
- id, type_contact_id (fk), contactable_type, contactable_id, value, timestamps, soft_deletes
- belongs to TypeContact
- morphTo contactable
```

### TypeDocument
```
- id, name, slug, order, timestamps, soft_deletes
```

### Document (polimórfica)
```
- id, type_document_id (fk), documentable_type, documentable_id, value, expires_at (date nullable), timestamps, soft_deletes
- belongs to TypeDocument
- morphTo documentable
```

### File (polimórfica)
```
- id, fileable_type, fileable_id, name, path, mime_type, size (unsignedBigInteger), timestamps, soft_deletes
- morphTo fileable
```

### Note (polimórfica)
```
- id, noteable_type, noteable_id, person_id (fk nullable), content (text), timestamps, soft_deletes
- belongs to Person (nullable)
- morphTo noteable
```

### Relationship
```
- id, name, slug, order, timestamps, soft_deletes
```

### PresenceMethod
```
- id, name, slug, order, timestamps, soft_deletes
```

### Feature
```
- id, name, slug, type (enum: gender, age_range, modality, cycle, mobility, profile, campus_access, location), order, timestamps, soft_deletes
```

### Campus
```
- id, name, slug, type (enum: main, filial), order, timestamps, soft_deletes
- has many Ministries
- morphMany Addresses, Contacts, Notes, Files
```

### Ministry
```
- id, campus_id (fk), name, slug, template (enum: padrao, loja, distribuicao, erp), order, timestamps, soft_deletes
- belongs to Campus
- has many Groups
- has many MinistryFeatures
- has many MinistryPersons
- morphMany Addresses, Contacts, Notes, Files
```

### Group
```
- id, ministry_id (fk), name, slug, order, timestamps, soft_deletes
- belongs to Ministry
- has many GroupFeatures
- has many GroupPersons
- has many Events
- morphMany Addresses, Contacts, Notes, Files
```

### MinistryFeature (pivot)
```
- id, ministry_id (fk), feature_id (fk), value (string), timestamps, soft_deletes
- belongs to Ministry
- belongs to Feature
```

### GroupFeature (pivot)
```
- id, group_id (fk), feature_id (fk), value (string), timestamps, soft_deletes
- belongs to Group
- belongs to Feature
```

Regra de herança: grupo herda features do ministério. GroupFeature só adiciona restrições extras, nunca contradiz o ministério. Ministério = masculino → todos os grupos são masculinos, ponto. Ministério = misto → grupo pode restringir pra masculino ou feminino.

### Person
```
- id, gender_id (fk), name, birth_date (date nullable), timestamps, soft_deletes
- belongs to Gender
- has one User
- has many MinistryPersons
- has many GroupPersons
- has many FamilyLinks (via person_id)
- has many FamilyLinks (via related_person_id)
- has many AuthorizedPickups (via child_person_id)
- has many Presences
- has many Achievements
- has many Finances
- morphMany Addresses, Contacts, Documents, Notes, Files
```

O ID da pessoa é transformado em código de 6 dígitos automaticamente (ex: id 42 → 000042). Usado no QR Code.

### User
```
- id, person_id (fk), email (unique), password, email_verified_at (nullable), timestamps, soft_deletes
- belongs to Person
- NÃO herda BaseModel, herda Authenticatable
- Usado APENAS para autenticação
```

Se a Person tem User vinculado → cadastro completo.
Se não tem User → convidado (registro mínimo via contacts).

### MinistryPerson (pivot)
```
- id, person_id (fk), ministry_id (fk), role_id (fk), status_id (fk), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes
- belongs to Person
- belongs to Ministry
- belongs to Role
- belongs to Status
```

### GroupPerson (pivot)
```
- id, person_id (fk), group_id (fk), role_id (fk), status_id (fk), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes
- belongs to Person
- belongs to Group
- belongs to Role
- belongs to Status
```

### FamilyLink
```
- id, person_id (fk), related_person_id (fk), relationship_id (fk), status_id (fk), timestamps, soft_deletes
- belongs to Person (de)
- belongs to Person (para)
- belongs to Relationship
- belongs to Status
```

### AuthorizedPickup
```
- id, child_person_id (fk), authorized_person_id (fk nullable), relationship_id (fk), name (string), timestamps, soft_deletes
- belongs to Person (criança)
- belongs to Person (autorizado — nullable, pode não existir no sistema)
- belongs to Relationship
- morphMany Contacts, Documents
```

### Event
```
- id, name (string), ministry_id (fk nullable), group_id (fk nullable), start_at (timestamp), end_at (timestamp), status_id (fk), timestamps, soft_deletes
- belongs to Ministry (nullable)
- belongs to Group (nullable)
- belongs to Status
- has many Presences
- has many ServiceRequests
- morphMany Addresses, Notes
```

### Presence (pivot person ↔ event)
```
- id, person_id (fk), event_id (fk), role_id (fk), presence_method_id (fk), start_at (timestamp), end_at (timestamp nullable), timestamps, soft_deletes
- belongs to Person
- belongs to Event
- belongs to Role
- belongs to PresenceMethod
```

### Achievement
```
- id, person_id (fk), ministry_id (fk nullable), group_id (fk nullable), achieved_at (timestamp), timestamps, soft_deletes
- belongs to Person
- belongs to Ministry (nullable)
- belongs to Group (nullable)
```

Título montado automaticamente com base nas features:
- Ministry is_achievement: true + Group is_achievement: true → "Concluiu {ministry} - {group}"
- Ministry is_achievement: true + Group is_achievement: false → "Concluiu {ministry}"
- Ministry is_achievement: false + Group is_achievement: true → "Concluiu {group}"
- Ambos false → não gera conquista

### FinanceType
```
- id, name, slug, order, timestamps, soft_deletes
```

### FinanceCategory
```
- id, finance_type_id (fk), name, slug, order, timestamps, soft_deletes
- belongs to FinanceType
```

### PaymentMethod
```
- id, name, slug, order, timestamps, soft_deletes
```

### Finance
```
- id, person_id (fk nullable), ministry_id (fk nullable), event_id (fk nullable), finance_type_id (fk), finance_category_id (fk nullable), amount (decimal 10,2), payment_method_id (fk), status_id (fk), external_id (string nullable), paid_at (timestamp nullable), timestamps, soft_deletes
- belongs to Person (nullable)
- belongs to Ministry (nullable)
- belongs to Event (nullable)
- belongs to FinanceType
- belongs to FinanceCategory (nullable)
- belongs to PaymentMethod
- belongs to Status
```

### ServiceRequest
```
- id, requester_ministry_id (fk), provider_ministry_id (fk), event_id (fk nullable), status_id (fk), start_at (timestamp), end_at (timestamp), timestamps, soft_deletes
- belongs to Ministry (requester)
- belongs to Ministry (provider)
- belongs to Event (nullable)
- belongs to Status
- has many ServiceAssignments
```

### ServiceAssignment
```
- id, service_request_id (fk), person_id (fk), role_id (fk), status_id (fk), timestamps, soft_deletes
- belongs to ServiceRequest
- belongs to Person
- belongs to Role
- belongs to Status
```

### AuditLog
```
- id, person_id (fk nullable), action (enum: created, updated, deleted), model (string), model_id (unsignedBigInteger), changes (json nullable), ip (string nullable), response (integer), created_at (timestamp)
- belongs to Person (nullable)
- Sem SoftDeletes — permanente
- Sem updated_at — só created_at
```

---

## Traits (Features)

```php
// Group
class Group extends BaseModel
{
    use HasFeatures, HasPresence, HasMembers, HasEvents, HasAddresses, HasContacts, HasNotes, HasFiles;
}

// Person
class Person extends BaseModel
{
    use HasTimeline, HasQrCode, HasAchievements, HasFamily, HasFinances, HasAddresses, HasContacts, HasDocuments, HasNotes, HasFiles;
}

// Ministry
class Ministry extends BaseModel
{
    use HasFeatures, HasGroups, HasEvents, HasAddresses, HasContacts, HasNotes, HasFiles;
}

// Campus
class Campus extends BaseModel
{
    use HasAddresses, HasContacts, HasNotes, HasFiles;
}

// AuthorizedPickup
class AuthorizedPickup extends BaseModel
{
    use HasContacts, HasDocuments;
}
```

| Trait | O que resolve |
|-------|--------------|
| HasFeatures | Gênero, idade, capacidade, pré-requisitos, localização |
| HasPresence | Registro de presença, relatórios de presença |
| HasMembers | Vínculos ministry_persons e group_persons |
| HasTimeline | Histórico cronológico da pessoa |
| HasQrCode | Geração e validação de QR Code (código 6 dígitos do ID) |
| HasAchievements | Conquistas e verificação de pré-requisitos |
| HasFamily | Vínculos familiares, solicitação e aceite |
| HasEvents | Agenda, eventos |
| HasFinances | Entradas e saídas financeiras |
| HasAddresses | Endereços polimórficos |
| HasContacts | Contatos polimórficos (email, telefone, whatsapp) |
| HasDocuments | Documentos polimórficos (CPF, RG, com validade) |
| HasFiles | Arquivos polimórficos |
| HasNotes | Notas/descrições polimórficas |
| HasAudit | Log automático de alterações (via BaseModel) |
| HasGroups | Relacionamento ministry → groups |

---

## Services

| Service | Responsabilidade |
|---------|-----------------|
| EnrollmentService | Cruza features da pessoa com ministry_features/group_features, valida capacidade, pré-requisitos. Inscreve em ministry_persons ou group_persons. |
| PresenceService | Registra presença por QR Code, manual ou Meet. Diferencia participante vs servindo. Enfileira em batch. |
| TimelineService | Monta timeline cronológica da pessoa (presenças, conquistas, vínculos, finanças). |
| ChildSafetyService | Valida se pessoa está na lista de authorized_pickups. Bloqueia e alerta se não está. |
| FamilyLinkService | Cria solicitação de vínculo, processa aceite/recusa. |
| FinanceService | Registra entradas/saídas financeiras. Placeholder para integração Asaas. |
| QrCodeService | Gera QR Code pessoal (6 dígitos do ID) e QR Code de grupo/evento. Valida na leitura. |
| ServiceRequestService | Cria convocação de ministério, aloca voluntários, processa aceite/recusa. |

---

## Policies

Tradução do Painel de Restrição em código. Checa permissão da **Person** (não do User):

```php
class GroupPolicy
{
    public function view(Person $person, Group $group)
    public function create(Person $person)
    public function update(Person $person, Group $group)
    public function delete(Person $person, Group $group)
    public function manageMembers(Person $person, Group $group)
    public function viewReports(Person $person, Group $group)
}
```

Policies existem para: Campus, Ministry, Group, Person, Presence.

---

## Migrations (ordem de criação)

### Banco Central (Stancl)
```
- tenants: id (string), name, slug, data (json nullable), timestamps
- domains: id, tenant_id (fk), domain, timestamps
```

### Banco do Tenant

#### Submódulos Base
```
00a - modules: id, name, slug, module_name, endpoint_name, order, timestamps, soft_deletes
00b - statuses: id, module_id (fk), name, slug, order, timestamps, soft_deletes
00c - roles: id, module_id (fk), name, slug, order, timestamps, soft_deletes
00d - genders: id, name, slug, order, timestamps, soft_deletes
00e - type_addresses: id, name, slug, order, timestamps, soft_deletes
00f - addresses: id, type_address_id (fk), addressable_type, addressable_id, zip_code, street, number (nullable), complement (nullable), neighborhood (nullable), city, state, country (default 'Brasil'), latitude (decimal nullable), longitude (decimal nullable), timestamps, soft_deletes
00g - type_contacts: id, name, slug, order, timestamps, soft_deletes
00h - contacts: id, type_contact_id (fk), contactable_type, contactable_id, value, timestamps, soft_deletes
00i - type_documents: id, name, slug, order, timestamps, soft_deletes
00j - documents: id, type_document_id (fk), documentable_type, documentable_id, value, expires_at (date nullable), timestamps, soft_deletes
00k - files: id, fileable_type, fileable_id, name, path, mime_type, size (unsignedBigInteger), timestamps, soft_deletes
00l - notes: id, noteable_type, noteable_id, person_id (fk nullable), content (text), timestamps, soft_deletes
00m - relationships: id, name, slug, order, timestamps, soft_deletes
00n - presence_methods: id, name, slug, order, timestamps, soft_deletes
00o - finance_types: id, name, slug, order, timestamps, soft_deletes
00p - finance_categories: id, finance_type_id (fk), name, slug, order, timestamps, soft_deletes
00q - payment_methods: id, name, slug, order, timestamps, soft_deletes
```

#### Core
```
01 - features: id, name, slug, type (enum: gender, age_range, modality, cycle, mobility, profile, campus_access, location), order, timestamps, soft_deletes
02 - campuses: id, name, slug, type (enum: main, filial), order, timestamps, soft_deletes
03 - ministries: id, campus_id (fk), name, slug, template (enum: padrao, loja, distribuicao, erp), order, timestamps, soft_deletes
04 - groups: id, ministry_id (fk), name, slug, order, timestamps, soft_deletes
05a - ministry_features: id, ministry_id (fk), feature_id (fk), value (string), timestamps, soft_deletes
05b - group_features: id, group_id (fk), feature_id (fk), value (string), timestamps, soft_deletes
06 - people: id, gender_id (fk), name, birth_date (date nullable), timestamps, soft_deletes
07 - users: id, person_id (fk), email (unique), password, email_verified_at (timestamp nullable), timestamps, soft_deletes
08 - ministry_persons: id, person_id (fk), ministry_id (fk), role_id (fk), status_id (fk), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes
09 - group_persons: id, person_id (fk), group_id (fk), role_id (fk), status_id (fk), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes
10 - family_links: id, person_id (fk), related_person_id (fk), relationship_id (fk), status_id (fk), timestamps, soft_deletes
11 - authorized_pickups: id, child_person_id (fk), authorized_person_id (fk nullable), relationship_id (fk), name (string), timestamps, soft_deletes
12 - events: id, name (string), ministry_id (fk nullable), group_id (fk nullable), start_at (timestamp), end_at (timestamp), status_id (fk), timestamps, soft_deletes
13 - presences: id, person_id (fk), event_id (fk), role_id (fk), presence_method_id (fk), start_at (timestamp), end_at (timestamp nullable), timestamps, soft_deletes
14 - achievements: id, person_id (fk), ministry_id (fk nullable), group_id (fk nullable), achieved_at (timestamp), timestamps, soft_deletes
15 - finances: id, person_id (fk nullable), ministry_id (fk nullable), event_id (fk nullable), finance_type_id (fk), finance_category_id (fk nullable), amount (decimal 10,2), payment_method_id (fk), status_id (fk), external_id (string nullable), paid_at (timestamp nullable), timestamps, soft_deletes
16a - service_requests: id, requester_ministry_id (fk), provider_ministry_id (fk), event_id (fk nullable), status_id (fk), start_at (timestamp), end_at (timestamp), timestamps, soft_deletes
16b - service_assignments: id, service_request_id (fk), person_id (fk), role_id (fk), status_id (fk), timestamps, soft_deletes
17 - audit_logs: id, person_id (fk nullable), action (enum: created, updated, deleted), model (string), model_id (unsignedBigInteger), changes (json nullable), ip (string nullable), response (integer), created_at (timestamp)
```

Todas com soft_deletes (exceto audit_logs). Rodam no banco de cada tenant.

---

## Middleware (fluxo do request)

```
Request → TenantResolve (Stancl) → AuthenticateApi (Sanctum) → ResolvePersonFromUser → CheckPermission → Controller
```

| Middleware | O que faz |
|-----------|----------|
| TenantResolve | Stancl identifica tenant pelo domínio, conecta ao banco |
| AuthenticateApi | Valida token Sanctum, resolve User |
| ResolvePersonFromUser | User → Person, disponível em todo request |
| CheckPermission | Person tem permissão pra essa ação? |
| EnsureActiveStatus | Person está ativa no tenant? |

---

## Auth (Sanctum)

```
POST /api/v2/auth/login          → retorna token
POST /api/v2/auth/logout         → revoga token
POST /api/v2/auth/register       → cria User + Person (ou vincula a Person existente pelo e-mail)
POST /api/v2/auth/forgot-password → envia reset
```

Token por dispositivo. Revogar um não afeta outro.

Registro: se e-mail já existe em contacts de alguma Person (convidado sem User), vincula User à Person existente. Timeline preservada.

---

## Rotas

```php
Route::prefix('v2')->group(function () {

    // Auth (público)
    Route::post('auth/login', [LoginController::class, 'login']);
    Route::post('auth/register', [RegisterController::class, 'register']);
    Route::post('auth/forgot-password', [ForgotPasswordController::class, 'send']);

    // Autenticado
    Route::middleware(['auth:sanctum', 'resolve.person', 'check.permission'])->group(function () {

        Route::post('auth/logout', [LogoutController::class, 'logout']);

        // CRUD genérico
        Route::apiResource('campus', CampusController::class);
        Route::apiResource('ministries', MinistryController::class);
        Route::apiResource('groups', GroupController::class);
        Route::apiResource('people', PersonController::class);
        Route::apiResource('events', EventController::class);
        Route::apiResource('features', FeatureController::class);

        // Aninhadas
        Route::apiResource('campus.ministries', CampusMinistryController::class);
        Route::apiResource('ministries.groups', MinistryGroupController::class);
        Route::apiResource('groups.people', GroupPersonController::class);

        // Inscrição
        Route::post('ministries/{ministry}/enroll', [EnrollmentController::class, 'enrollMinistry']);
        Route::post('groups/{group}/enroll', [EnrollmentController::class, 'enrollGroup']);

        // Presença
        Route::post('events/{event}/presence', [PresenceController::class, 'register']);
        Route::post('events/{event}/presence/batch', [PresenceController::class, 'registerBatch']);

        // Timeline
        Route::get('people/{person}/timeline', [TimelineController::class, 'index']);

        // Família
        Route::post('people/{person}/family-link', [FamilyLinkController::class, 'store']);
        Route::put('family-links/{familyLink}/respond', [FamilyLinkController::class, 'respond']);

        // Segurança de crianças
        Route::post('children/{child}/validate-pickup', [ChildSafetyController::class, 'validate']);

        // Servir Bem
        Route::post('service-requests', [ServiceRequestController::class, 'store']);
        Route::put('service-requests/{serviceRequest}/respond', [ServiceRequestController::class, 'respond']);
        Route::post('service-assignments', [ServiceAssignmentController::class, 'store']);
        Route::put('service-assignments/{serviceAssignment}/respond', [ServiceAssignmentController::class, 'respond']);

        // Financeiro
        Route::post('finances', [FinanceController::class, 'store']);
        Route::get('people/{person}/finances', [FinanceController::class, 'history']);

        // Restore (soft delete)
        Route::post('campus/{campus}/restore', [CampusController::class, 'restore']);
        Route::post('ministries/{ministry}/restore', [MinistryController::class, 'restore']);
        Route::post('groups/{group}/restore', [GroupController::class, 'restore']);
        Route::post('people/{person}/restore', [PersonController::class, 'restore']);
    });
});
```

---

## Observers e Events

### Observers (automático ao salvar)
| Observer | Trigger |
|----------|---------|
| PersonObserver | Criou pessoa → gera QR Code (6 dígitos do ID) |
| MinistryPersonObserver | Mudou status → atualiza timeline |
| GroupPersonObserver | Mudou status para concluído → dispara PersonCompleted, gera Achievement |
| PresenceObserver | Registrou presença → dispara PresenceRegistered |
| AchievementObserver | Conquista criada → checa portas que abriu |

### Events e Listeners
| Event | Listener |
|-------|---------|
| PersonEnrolled | UpdateTimeline, NotifyLeader |
| PersonCompleted | UpdateTimeline, CheckPrerequisites |
| PresenceRegistered | UpdateTimeline |
| ChildPickupAttempt | AlertChildSafety |
| FamilyLinkRequested | NotifyLeader |
| ServiceAssigned | NotifyLeader |
| FinanceReceived | UpdateTimeline |

---

## Jobs/Queues

| Job | Quando |
|-----|--------|
| ProcessPresenceBatchJob | Presença em massa (culto 5000 pessoas) |
| ProcessFinanceJob | Pagamento confirmado via webhook |
| GenerateQrCodeJob | QR Codes em lote |
| GenerateReportJob | Relatórios do Painel da Liderança |
| SyncGoogleCalendarJob | Sincroniza eventos (fase integrações) |
| SyncGoogleMeetJob | Cria/encerra salas (fase integrações) |
| CleanupExpiredTokensJob | Limpa tokens antigos |

Queue: Redis. Prioridade: presença e finanças são filas separadas com workers dedicados.

---

## Cache (Redis)

### Cacheia (muda pouco)
- Features do tenant
- Ministérios (estrutura, features, config)
- Grupos (estrutura, features, membros, capacidade)
- Hierarquia campus → ministério → grupo
- Permissões da Person
- Lista de autorizados por criança
- Pages (pública e restrita)

### Não cacheia (muda sempre)
- Presença (escrita constante, vai pra fila)
- Timeline (leitura sob demanda)
- Finanças (transacional)

Invalidação por evento — quando algo muda, flush no cache relacionado:
```php
Cache::tags(["ministry.{$id}"])->flush();
```

---

## Rate Limiting

| Contexto | Limite |
|----------|--------|
| Global (visitante) | 60 req/min por IP |
| Autenticado | 120 req/min por token |
| Presença | Sem limite (pico 30 Semanas) |
| Auth/Login | 5 tentativas/min |
| Finanças | 10 req/min |

---

## CORS

```
Libera:
- *.bethel360.com.br (subdomínios dos tenants)
- Domínios próprios (white-label, dinâmico por tenant)
- App mobile (qualquer origem, token valida)
```

---

## Logging/Auditoria

```
AuditLog:
- person_id (quem fez)
- action (created/updated/deleted)
- model (em qual tabela)
- model_id (qual registro)
- changes (json: before/after)
- ip (de onde)
- response (status HTTP)
- created_at (quando)
```

Automático via trait HasAudit no BaseModel. Todo model registra automaticamente.

---

## Seeders (rodam ao criar tenant)

### ModuleSeeder
- Central de Vidas, Ministérios, Grupos, Inscrições, Família, Escalas, Financeiro, Presença, Eventos, etc.

### StatusSeeder (por módulo)
- person: ativo, inativo, pendente
- ministry: ativo, inativo
- enrollment: ativo, inativo, pendente, concluído
- family_link: pendente, aceito, recusado
- schedule: pendente, aceito, recusado
- payment: pendente, confirmado, cancelado
- event: ativo, cancelado, concluído

### RoleSeeder (por módulo)
- enrollment: participante, líder, voluntário
- system: admin, líder_ministério, líder_grupo
- presence: participante, servindo

### GenderSeeder
- Masculino, Feminino

### FeatureSeeder
- Gênero (masculino, feminino, misto)
- Faixa etária (ranges configuráveis)
- Modalidade (presencial, online)
- Ciclo (aberto, fechado)
- Mobilidade (livre, fechada)

### RelationshipSeeder
- Cônjuge, Filho, Pai, Mãe, Irmão, Tio, Avó

### PresenceMethodSeeder
- QR Code, Manual, Meet, Geolocalização

### FinanceTypeSeeder
- Income, Expense

### FinanceCategorySeeder
- Income: Dízimo, Oferta, Oferta Farol
- Expense: Aluguel, Energia, Salário, Equipamento

### PaymentMethodSeeder
- PIX, Cartão de Crédito, Cartão de Débito, Boleto

### TypeAddressSeeder
- Residencial, Comercial

### TypeContactSeeder
- E-mail, Telefone, WhatsApp, Instagram

### TypeDocumentSeeder
- CPF, RG, CNH, Certidão de Nascimento

---

## Error Handling

Resposta padrão centralizada:

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

---

## Soft Deletes

Todos os models usam SoftDeletes via BaseModel. Nenhum dado é apagado definitivamente. AuditLog nunca sofre delete.

---

## Versionamento

Versiona (contrato externo):
- Routes → /api/v2/...
- Controllers → V2/
- Resources → V2/

Não versiona (interno):
- Models, Migrations, Services, Traits, Policies, Jobs

---

## API Docs

L5-Swagger gera documentação automática a partir de annotations nos Controllers. Acesso em /api/documentation.
