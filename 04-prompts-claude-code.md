# Bethel360° — Prompts para Claude Code

---

## PROMPT 1 — FUNDAÇÃO (Manhã)

```
Crie um projeto Laravel 11 chamado bethel360-api com a seguinte fundação:

## 1. Setup Inicial
- Laravel 11, PHP 8.3+
- Instale e configure: stancl/tenancy (multi-tenant com banco separado por tenant), laravel/sanctum (auth API), darkaonline/l5-swagger
- Configure Redis como driver de cache e queue
- Configure o Stancl para resolver tenant por domínio/subdomínio ({slug}.bethel360.com.br)

## 2. BaseModel
Crie app/Models/BaseModel.php que todo model vai herdar:
- use SoftDeletes
- use HasAudit (trait que será criada)

## 3. Migrations (tenant — rodam no banco de cada igreja)
Crie na seguinte ordem, todas com soft_deletes e timestamps (exceto audit_logs):

### Submódulos Base

00a - modules: id, name (string), slug (string), module_name (string), endpoint_name (string), order (integer default 0), timestamps, soft_deletes

00b - statuses: id, module_id (fk), name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00c - roles: id, module_id (fk), name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00d - genders: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00e - type_addresses: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00f - addresses: id, type_address_id (fk), addressable_type (string), addressable_id (unsignedBigInteger), zip_code (string), street (string), number (string nullable), complement (string nullable), neighborhood (string nullable), city (string), state (string), country (string default 'Brasil'), latitude (decimal 10,7 nullable), longitude (decimal 10,7 nullable), timestamps, soft_deletes — INDEX em (addressable_type, addressable_id)

00g - type_contacts: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00h - contacts: id, type_contact_id (fk), contactable_type (string), contactable_id (unsignedBigInteger), value (string), timestamps, soft_deletes — INDEX em (contactable_type, contactable_id)

00i - type_documents: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00j - documents: id, type_document_id (fk), documentable_type (string), documentable_id (unsignedBigInteger), value (string), expires_at (date nullable), timestamps, soft_deletes — INDEX em (documentable_type, documentable_id)

00k - files: id, fileable_type (string), fileable_id (unsignedBigInteger), name (string), path (string), mime_type (string), size (unsignedBigInteger), timestamps, soft_deletes — INDEX em (fileable_type, fileable_id)

00l - notes: id, noteable_type (string), noteable_id (unsignedBigInteger), person_id (fk people nullable), content (text), timestamps, soft_deletes — INDEX em (noteable_type, noteable_id)

00m - relationships: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00n - presence_methods: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00o - finance_types: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00p - finance_categories: id, finance_type_id (fk), name (string), slug (string), order (integer default 0), timestamps, soft_deletes

00q - payment_methods: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

### Core

01 - features: id, name (string), slug (string), type (enum: gender, age_range, modality, cycle, mobility, profile, campus_access, location), order (integer default 0), timestamps, soft_deletes

02 - campuses: id, name (string), slug (string), type (enum: main, filial), order (integer default 0), timestamps, soft_deletes

03 - ministries: id, campus_id (fk campuses), name (string), slug (string), template (enum: padrao, loja, distribuicao, erp), order (integer default 0), timestamps, soft_deletes

04 - groups: id, ministry_id (fk ministries), name (string), slug (string), order (integer default 0), timestamps, soft_deletes

05a - ministry_features: id, ministry_id (fk ministries), feature_id (fk features), value (string), timestamps, soft_deletes

05b - group_features: id, group_id (fk groups), feature_id (fk features), value (string), timestamps, soft_deletes

06 - people: id, gender_id (fk genders), name (string), birth_date (date nullable), timestamps, soft_deletes

07 - users: id, person_id (fk people), email (string unique), password (string), email_verified_at (timestamp nullable), timestamps, soft_deletes

08 - ministry_persons: id, person_id (fk people), ministry_id (fk ministries), role_id (fk roles), status_id (fk statuses), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes

09 - group_persons: id, person_id (fk people), group_id (fk groups), role_id (fk roles), status_id (fk statuses), enrolled_at (timestamp), completed_at (timestamp nullable), timestamps, soft_deletes

10 - family_links: id, person_id (fk people), related_person_id (fk people), relationship_id (fk relationships), status_id (fk statuses), timestamps, soft_deletes

11 - authorized_pickups: id, child_person_id (fk people), authorized_person_id (fk people nullable), relationship_id (fk relationships), name (string), timestamps, soft_deletes

12 - events: id, name (string), ministry_id (fk ministries nullable), group_id (fk groups nullable), start_at (timestamp), end_at (timestamp), status_id (fk statuses), timestamps, soft_deletes

13 - presences: id, person_id (fk people), event_id (fk events), role_id (fk roles), presence_method_id (fk presence_methods), start_at (timestamp), end_at (timestamp nullable), timestamps, soft_deletes

14 - achievements: id, person_id (fk people), ministry_id (fk ministries nullable), group_id (fk groups nullable), achieved_at (timestamp), timestamps, soft_deletes

15 - finances: id, person_id (fk people nullable), ministry_id (fk ministries nullable), event_id (fk events nullable), finance_type_id (fk finance_types), finance_category_id (fk finance_categories nullable), amount (decimal 10,2), payment_method_id (fk payment_methods), status_id (fk statuses), external_id (string nullable), paid_at (timestamp nullable), timestamps, soft_deletes

16a - service_requests: id, requester_ministry_id (fk ministries), provider_ministry_id (fk ministries), event_id (fk events nullable), status_id (fk statuses), start_at (timestamp), end_at (timestamp), timestamps, soft_deletes

16b - service_assignments: id, service_request_id (fk service_requests), person_id (fk people), role_id (fk roles), status_id (fk statuses), timestamps, soft_deletes

17 - audit_logs: id, person_id (fk people nullable), action (enum: created, updated, deleted), model (string), model_id (unsignedBigInteger), changes (json nullable), ip (string nullable), response (integer), created_at (timestamp) — SEM updated_at, SEM soft_deletes, permanente

IMPORTANTE: a tabela notes referencia people (person_id), mas people é criada depois. Use uma migration separada para adicionar a FK de notes.person_id APÓS a criação da tabela people.

## 4. Models
Crie todos os models herdando de BaseModel, com fillable, casts, e relacionamentos conforme as migrations acima:

### Submódulos Base
- Module (hasMany Status, hasMany Role)
- Status (belongsTo Module)
- Role (belongsTo Module)
- Gender
- TypeAddress (hasMany Address)
- Address (belongsTo TypeAddress, morphTo addressable)
- TypeContact (hasMany Contact)
- Contact (belongsTo TypeContact, morphTo contactable)
- TypeDocument (hasMany Document)
- Document (belongsTo TypeDocument, morphTo documentable)
- File (morphTo fileable)
- Note (belongsTo Person nullable, morphTo noteable)
- Relationship
- PresenceMethod
- FinanceType (hasMany FinanceCategory)
- FinanceCategory (belongsTo FinanceType)
- PaymentMethod

### Core
- Campus (hasMany Ministry, morphMany Address/Contact/Note/File)
- Ministry (belongsTo Campus, hasMany Group, hasMany MinistryFeature, hasMany MinistryPerson, morphMany Address/Contact/Note/File)
- Group (belongsTo Ministry, hasMany GroupFeature, hasMany GroupPerson, hasMany Event, morphMany Address/Contact/Note/File)
- MinistryFeature (belongsTo Ministry, belongsTo Feature)
- GroupFeature (belongsTo Group, belongsTo Feature)
- Person (belongsTo Gender, hasOne User, hasMany MinistryPerson, hasMany GroupPerson, hasMany FamilyLink via person_id, hasMany FamilyLink via related_person_id, hasMany AuthorizedPickup via child_person_id, hasMany Presence, hasMany Achievement, hasMany Finance, morphMany Address/Contact/Document/Note/File)
- User (belongsTo Person) — NÃO herda BaseModel, herda Authenticatable padrão do Laravel, mas usa SoftDeletes
- MinistryPerson (belongsTo Person, belongsTo Ministry, belongsTo Role, belongsTo Status)
- GroupPerson (belongsTo Person, belongsTo Group, belongsTo Role, belongsTo Status)
- FamilyLink (belongsTo Person via person_id, belongsTo Person via related_person_id, belongsTo Relationship, belongsTo Status)
- AuthorizedPickup (belongsTo Person via child_person_id, belongsTo Person via authorized_person_id nullable, belongsTo Relationship, morphMany Contact/Document)
- Event (belongsTo Ministry nullable, belongsTo Group nullable, belongsTo Status, hasMany Presence, hasMany ServiceRequest, morphMany Address/Note)
- Presence (belongsTo Person, belongsTo Event, belongsTo Role, belongsTo PresenceMethod)
- Achievement (belongsTo Person, belongsTo Ministry nullable, belongsTo Group nullable)
- Finance (belongsTo Person nullable, belongsTo Ministry nullable, belongsTo Event nullable, belongsTo FinanceType, belongsTo FinanceCategory nullable, belongsTo PaymentMethod, belongsTo Status)
- ServiceRequest (belongsTo Ministry via requester_ministry_id, belongsTo Ministry via provider_ministry_id, belongsTo Event nullable, belongsTo Status, hasMany ServiceAssignment)
- ServiceAssignment (belongsTo ServiceRequest, belongsTo Person, belongsTo Role, belongsTo Status)
- AuditLog (belongsTo Person nullable) — sem SoftDeletes, permanente, sem updated_at

## 5. Auth (Sanctum)
Configure Sanctum para API token auth. Crie:
- LoginController: recebe email+senha, valida, retorna token Sanctum. Se User não existe mas existe Person com aquele email em contacts (convidado sem User), retorna erro orientando cadastro completo.
- RegisterController: recebe dados completos. Se email já existe em contacts de uma Person (convidado sem User), vincula User à Person existente. Se não existe, cria Person + User. O ID da pessoa vira código de 6 dígitos (id 42 → 000042) usado no QR Code.
- LogoutController: revoga token atual.
- ForgotPasswordController: placeholder com TODO.

## 6. Middleware
- AuthenticateApi: valida token Sanctum (use o middleware padrão auth:sanctum)
- ResolvePersonFromUser: após auth, resolve User->person e injeta no request como $request->person
- CheckPermission: placeholder que verifica se person tem permissão (inicialmente retorna true pra tudo — implementação real virá com Policies)
- EnsureActiveStatus: verifica se person não está com todos os vínculos inativos

Registre os middleware nas rotas API.

## 7. Seeders (TenantSeeder)
Crie TenantSeeder que roda ao criar tenant, chamando:

### ModuleSeeder
Cria módulos:
- Central de Vidas (slug: central-de-vidas, module_name: persons, endpoint_name: people)
- Ministérios (slug: ministerios, module_name: ministries, endpoint_name: ministries)
- Grupos (slug: grupos, module_name: groups, endpoint_name: groups)
- Inscrições (slug: inscricoes, module_name: enrollments, endpoint_name: enrollments)
- Família (slug: familia, module_name: family_links, endpoint_name: family-links)
- Escalas (slug: escalas, module_name: service_assignments, endpoint_name: service-assignments)
- Financeiro (slug: financeiro, module_name: finances, endpoint_name: finances)
- Presença (slug: presenca, module_name: presences, endpoint_name: presences)
- Eventos (slug: eventos, module_name: events, endpoint_name: events)

### StatusSeeder (por módulo)
- person: ativo, inativo, pendente
- ministry: ativo, inativo
- group: ativo, inativo
- enrollment: ativo, inativo, pendente, concluído
- family_link: pendente, aceito, recusado
- service_assignment: pendente, aceito, recusado
- payment: pendente, confirmado, cancelado
- event: ativo, cancelado, concluído
- service_request: pendente, aceito, recusado

### RoleSeeder (por módulo)
- enrollment: participante, líder, voluntário
- system: admin, líder_ministério, líder_grupo
- presence: participante, servindo

### GenderSeeder
- Masculino, Feminino

### FeatureSeeder
- Gênero: masculino, feminino, misto
- Faixa etária: 0-11, 12-14, 15-17, 18+
- Modalidade: presencial, online
- Ciclo: aberto, fechado
- Mobilidade: livre, fechada

### RelationshipSeeder
- Cônjuge, Filho(a), Pai, Mãe, Irmão(ã), Tio(a), Avô(ó)

### PresenceMethodSeeder
- QR Code, Manual, Meet, Geolocalização

### FinanceTypeSeeder
- Entrada (income), Saída (expense)

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

Garanta que tudo funciona: migrate, seed, login, logout, register.
```

---

## PROMPT 2 — CORE (Tarde)

```
Continuando o projeto bethel360-api, implemente a camada core da API:

## 1. Traits (app/Features/)
Crie os seguintes traits:

### HasAudit
- Usa model events (created, updated, deleted) para registrar automaticamente em audit_logs
- Captura person_id do request (se autenticado), action, model class, model_id, changes (before/after), ip, response (status HTTP)

### HasFeatures
- Método features(): retorna MinistryFeatures ou GroupFeatures do model conforme o tipo
- Método matchesProfile(Person $person): cruza features do model com perfil da pessoa (gênero, idade) e retorna true/false
- Método availableCapacity(): retorna vagas restantes (busca feature capacity - membros ativos)
- REGRA: grupo herda features do ministério. GroupFeature só adiciona restrições extras, nunca contradiz. Ministério = masculino → todos os grupos masculinos.

### HasMembers
- Método ministryPersons() ou groupPersons(): retorna vínculos conforme o model
- Método activeMembersCount(): conta membros ativos
- Método enroll(Person, role_id, status_id): cria MinistryPerson ou GroupPerson
- Método updateMemberStatus(Person, status_id): atualiza status

### HasPresence
- Método presences(): retorna presenças do model
- Método registerPresence(Person, role_id, presence_method_id): registra presença

### HasTimeline
- Método timeline(): retorna merge ordenado de presenças, conquistas, vínculos (ministry_persons, group_persons), finanças da pessoa

### HasQrCode
- O QR Code é o ID da pessoa formatado em 6 dígitos (id 42 → "000042")
- Método getQrCode(): retorna código de 6 dígitos
- Método static resolveFromQrCode(string $code): encontra a pessoa pelo código

### HasAchievements
- Método achievements(): retorna conquistas
- Método hasCompleted(ministry_id ou group_id): verifica se concluiu
- Método grantAchievement(ministry_id, group_id): concede conquista
- Título montado automaticamente: ministry is_achievement + group is_achievement → "Concluiu {ministry} - {group}" ou variações

### HasFamily
- Método familyLinks(): retorna vínculos aceitos
- Método pendingFamilyRequests(): retorna pendentes
- Método authorizedPickups(): retorna autorizados (para crianças)

### HasEvents
- Método events(): retorna eventos
- Método upcomingEvents(): retorna próximos eventos

### HasFinances
- Método finances(): retorna movimentações financeiras
- Método financesByType(finance_type_id): filtra por tipo (income/expense)

### HasGroups (para Ministry)
- Método groups(): retorna grupos do ministério

### HasAddresses
- morphMany Address::class, 'addressable'

### HasContacts
- morphMany Contact::class, 'contactable'

### HasDocuments
- morphMany Document::class, 'documentable'

### HasFiles
- morphMany File::class, 'fileable'

### HasNotes
- morphMany Note::class, 'noteable'

## 2. BaseController (app/Http/Controllers/V2/BaseController.php)
Implemente o CRUD genérico completo:

### index()
- Paginação (?page=N&per_page=N, default 15)
- Filtros (?filter[campo]=valor) — aplica where dinâmico
- Busca (?search=texto) — busca em name e email (configurável por controller)
- Ordenação (?sort=campo ou ?sort=-campo para desc)
- Includes (?include=relacao1,relacao2) — eager loading
- Retorna via Resource

### show($id)
- Suporta ?include=
- Retorna via Resource

### store()
- Valida via Request class (se definida)
- Cria registro
- Retorna via Resource, status 201

### update($id)
- Valida via Request class
- Atualiza registro
- Retorna via Resource

### destroy($id)
- Soft delete
- Retorna status 204

### restore($id)
- Restaura soft delete
- Retorna via Resource

O controller filho só define:
```php
protected $model = Ministry::class;
protected $request = MinistryRequest::class;
protected $resource = MinistryResource::class;
protected $searchableFields = ['name'];
```

## 3. Resources (app/Http/Resources/V2/)
Crie Resources para cada model. Formato padrão:
```json
{
    "data": { "id": 1, "name": "...", ... },
    "meta": { ... }
}
```
Para collections:
```json
{
    "data": [ { ... }, { ... } ],
    "meta": { "pagination": { "total": 100, "per_page": 15, "current_page": 1 } }
}
```

Crie: CampusResource, MinistryResource, GroupResource, PersonResource, PersonGuestResource (campos mínimos), PresenceResource, TimelineResource, AchievementResource, FinanceResource, EventResource, FamilyLinkResource, ServiceRequestResource, ServiceAssignmentResource.

## 4. Controllers (app/Http/Controllers/V2/)
Todos herdam BaseController. Crie:

### CRUD simples (só define model, request, resource):
- CampusController
- MinistryController
- GroupController
- PersonController
- FeatureController
- EventController

### Com ações específicas:
- EnrollmentController: enrollMinistry(Ministry, Person) e enrollGroup(Group, Person) — usa EnrollmentService
- PresenceController: register(Event) — presença por QR code/manual. registerBatch(Event) — presença em massa
- TimelineController: index(Person) — retorna timeline
- FamilyLinkController: store(Person) — solicita vínculo. respond(FamilyLink) — aceita/recusa
- ChildSafetyController: validate(child) — valida se pessoa pode retirar criança
- ServiceRequestController: store() — cria convocação de ministério. respond(ServiceRequest) — aceita/recusa
- ServiceAssignmentController: store() — aloca voluntário. respond(ServiceAssignment) — aceita/recusa
- FinanceController: store() — registra movimentação. history(Person) — histórico

### Aninhados:
- CampusMinistryController: lista ministérios de um campus
- MinistryGroupController: lista grupos de um ministério
- GroupPersonController: lista pessoas de um grupo

## 5. Rotas (routes/api.php)
Configure todas as rotas conforme definido:
- Auth público: login, register, forgot-password
- CRUD autenticado: campus, ministries, groups, people, events, features
- Aninhadas: campus.ministries, ministries.groups, groups.people
- Inscrição: POST ministries/{ministry}/enroll, POST groups/{group}/enroll
- Presença: POST events/{event}/presence, POST events/{event}/presence/batch
- Timeline: GET people/{person}/timeline
- Família: POST people/{person}/family-link, PUT family-links/{familyLink}/respond
- Segurança: POST children/{child}/validate-pickup
- Servir Bem: POST/PUT service-requests, POST/PUT service-assignments
- Financeiro: POST finances, GET people/{person}/finances
- Restore: POST {resource}/{id}/restore

Middleware stack: auth:sanctum, resolve.person, check.permission

## 6. Services
Implemente a lógica de negócio:

### EnrollmentService
- enrollInMinistry(Ministry, Person): verifica ministry_features (matchesProfile), pré-requisitos (hasCompleted). Se tudo ok, cria MinistryPerson. Se não, retorna erro específico.
- enrollInGroup(Group, Person): verifica group_features + herança de ministry_features, capacidade (availableCapacity), pré-requisitos. Se tudo ok, cria GroupPerson.

### PresenceService
- registerByQrCode(Event, qrCode): resolve pessoa pelo código 6 dígitos, registra presença
- registerManual(Event, email_or_code): fallback manual
- registerBatch(Event, array qrCodes): dispatch ProcessPresenceBatchJob

### ChildSafetyService
- validatePickup(Person child, Person requester): checa AuthorizedPickup. Se não autorizado, dispara evento ChildPickupAttempt e retorna false com alerta.
- validatePickupByName(Person child, string name): para autorizados sem cadastro no sistema — validação manual pelo líder.

### TimelineService
- build(Person): merge ordenado de presenças, conquistas, ministry_persons, group_persons (inscrições/conclusões), finanças. Respeita is_achievement das features (ministry/group).

### FamilyLinkService
- request(Person from, Person to, relationship_id): cria FamilyLink pendente, dispara FamilyLinkRequested
- respond(FamilyLink, status_id): atualiza status (aceito/recusado)

### FinanceService
- create(Person, finance_type_id, finance_category_id, amount, ministry_id, payment_method_id): cria Finance pendente. Placeholder para integração Asaas.

### QrCodeService
- generateForPerson(Person): retorna código 6 dígitos do ID
- resolve(string code): retorna Person pelo código

### ServiceRequestService
- createRequest(Ministry requester, Ministry provider, event_id, start_at, end_at): cria ServiceRequest pendente
- respondRequest(ServiceRequest, status_id): aceita/recusa convocação
- assignPerson(ServiceRequest, Person, role_id): cria ServiceAssignment pendente, dispara ServiceAssigned
- respondAssignment(ServiceAssignment, status_id): voluntário aceita/recusa

Garanta que todos os endpoints funcionam: CRUD, enrollment, presença, timeline, family links, child safety, servir bem, financeiro.
```

---

## PROMPT 3 — INFRAESTRUTURA (Noite)

```
Continuando o projeto bethel360-api, implemente a camada de infraestrutura:

## 1. Policies (app/Policies/)
Implemente authorization baseada na Person (não User):

### CampusPolicy
- view: qualquer pessoa autenticada do tenant
- create/update/delete: person com permissão painel_restricao

### MinistryPolicy
- view: qualquer pessoa autenticada
- create/update/delete: person com permissão painel_restricao OU líder do ministério (role líder em ministry_persons)
- viewReports: líder do ministério ou superior

### GroupPolicy
- view: qualquer pessoa autenticada (respeita is_confidential via group_features — se true, só membros veem)
- create/update/delete: líder do ministério pai
- manageMembers: líder do grupo (role líder em group_persons) ou líder do ministério
- viewReports: líder do grupo ou superior

### PersonPolicy
- view: a própria pessoa OU líder de algum grupo/ministério onde ela participa
- update: a própria pessoa OU admin
- delete: admin apenas
- viewTimeline: a própria pessoa OU líder de grupo/ministério (respeitando is_achievement das features)

### PresencePolicy
- register: líder do grupo (para grupo pequeno) OU qualquer autenticado (para culto via QR code do telão)

Registre as policies no AuthServiceProvider. Use $this->authorizeResource() nos controllers.

Para checar permissões: crie tabela permissions e person_permissions (pivot). O CheckPermission middleware consulta essa tabela. Crie a migration e model correspondentes.

## 2. Observers (app/Observers/)

### PersonObserver
- created: gera código QR de 6 dígitos a partir do ID

### MinistryPersonObserver
- created: adiciona entrada na timeline (inscrito em ministério X)
- updated: se status mudou para concluído, dispara PersonCompleted event e gera Achievement (se ministry_feature is_achievement = true)

### GroupPersonObserver
- created: adiciona entrada na timeline (inscrito em grupo X)
- updated: se status mudou para concluído, dispara PersonCompleted event e gera Achievement conforme regras de is_achievement (ministry + group features)

### PresenceObserver
- created: dispara PresenceRegistered event

### AchievementObserver
- created: verifica se a conquista desbloqueia algo (checa ministry_features/group_features de outros grupos que exigem pré-requisito)

Registre todos os observers no EventServiceProvider (ou boot do model).

## 3. Events e Listeners

### Events
Crie os event classes com os dados necessários:
- PersonEnrolled(Person, Ministry ou Group)
- PersonCompleted(Person, Ministry, Group nullable)
- PresenceRegistered(Presence)
- ChildPickupAttempt(Person child, Person requester nullable, string name, bool authorized)
- FamilyLinkRequested(FamilyLink)
- ServiceAssigned(ServiceAssignment)
- FinanceReceived(Finance)

### Listeners
- UpdateTimeline: escuta PersonEnrolled, PersonCompleted, PresenceRegistered, FinanceReceived. Placeholder — a timeline é montada via query, mas pode cachear.
- CheckPrerequisites: escuta PersonCompleted. Verifica se a conquista habilita algo novo.
- NotifyLeader: escuta PersonEnrolled, FamilyLinkRequested, ServiceAssigned. Placeholder para notificação.
- AlertChildSafety: escuta ChildPickupAttempt. Se não autorizado, loga como crítico, cria audit_log especial.

Registre no EventServiceProvider.

## 4. Jobs (app/Jobs/)

### ProcessPresenceBatchJob
- Recebe array de QR codes (6 dígitos) + event_id
- Resolve cada código para Person
- Registra presença de cada um
- Tolerante a falhas — se um código falha, continua os outros e loga o erro

### GenerateQrCodeJob
- Recebe person_id
- Gera código 6 dígitos a partir do ID
- Placeholder para gerar imagem do QR Code se necessário

### ProcessFinanceJob
- Placeholder para processar callback do Asaas
- Recebe external_id, atualiza status para confirmado

### GenerateReportJob
- Placeholder — recebe parâmetros (ministry_id, date_range) e gera relatório

### CleanupExpiredTokensJob
- Limpa personal_access_tokens expirados (mais de 30 dias sem uso)

Configure as filas no config/queue.php:
- default: jobs gerais
- presence: ProcessPresenceBatchJob (prioridade alta)
- payments: ProcessFinanceJob

## 5. Cache Strategy
Configure cache com Redis e implemente:

### CacheService ou via Trait
- Cachear ao carregar: features do tenant (tag: tenant_features), hierarquia campus>ministry>group (tag: hierarchy), ministry_features (tag: ministry.{id}), group_features (tag: group.{id}), lista de authorized_pickups por criança (tag: child_safety.{child_id}), permissões da person (tag: person_permissions.{id})
- TTL: 1 hora para features e hierarquia, 30 min para permissões, 15 min para child safety
- Invalidar cache nos Observers quando dados mudam (ex: MinistryFeature salva → flush tag do ministry)

Adicione cache nos Services e Controllers onde faz sentido (especialmente EnrollmentService que consulta features, e ChildSafetyService).

## 6. Rate Limiting
Configure no bootstrap/app.php (Laravel 11):
- 'global': 60/min por IP
- 'authenticated': 120/min por token
- 'auth-login': 5/min por IP
- 'finances': 10/min por token
- Rota de presença: SEM rate limit

Aplique nos grupos de rotas correspondentes.

## 7. CORS
Configure config/cors.php:
- allowed_origins: ['*.bethel360.com.br'] + domínios white-label (dinâmico via tenant)
- allowed_methods: ['GET', 'POST', 'PUT', 'PATCH', 'DELETE']
- allowed_headers: ['Content-Type', 'Authorization', 'Accept']
- supports_credentials: true

## 8. Error Handling
Configure bootstrap/app.php (Laravel 11):
- ModelNotFoundException → 404 { "message": "Registro não encontrado", "status": 404 }
- AuthenticationException → 401 { "message": "Não autenticado", "status": 401 }
- AuthorizationException → 403 { "message": "Sem permissão", "status": 403 }
- ValidationException → 422 { "message": "Dados inválidos", "errors": { ... }, "status": 422 }
- Throwable → 500 { "message": "Erro interno do servidor", "status": 500 }
- Sempre JSON, nunca HTML

## 9. Seeders Completos
Garanta que o TenantSeeder criado no Prompt 1 está completo e rodando todos os seeders listados.

## 10. Testes
Crie testes para os fluxos críticos:

### Feature Tests
- Auth/LoginTest: login com credenciais válidas e inválidas, logout
- Auth/RegisterTest: registro novo, registro vinculando convidado existente
- ChildSafety/PickupAuthorizationTest: autorizado libera, não autorizado bloqueia e dispara alerta, autorizado sem cadastro no sistema validação manual
- Enrollment/EnrollmentTest: inscrição em ministério, inscrição em grupo com features compatíveis aceita, incompatíveis rejeita, capacidade cheia rejeita, herança de features do ministério
- Presence/PresenceTest: presença por QR code (6 dígitos), manual, batch
- ServiceRequest/ServiceRequestTest: convocação, alocação, aceite/recusa
- CRUD básicos para Campus, Ministry, Group, Person

### Unit Tests
- EnrollmentServiceTest
- ChildSafetyServiceTest
- PresenceServiceTest
- ServiceRequestServiceTest

Use RefreshDatabase. Configure tenant de teste no setup.

Rode todos os testes e garanta que passam. Se algum falhar, corrija até todos ficarem verdes.
```
