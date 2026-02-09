# Bethel360° — Banco Central

---

## Visão Geral

O banco central (`bethel360_central`) armazena tudo que é da **plataforma Bethel360°** — não da igreja. Aqui vivem os tenants, planos, domínios, financeiro da plataforma e os dados dos responsáveis que contratam o sistema.

Usa os mesmos submódulos polimórficos do banco do tenant (addresses, contacts, documents, files, notes).

---

## Migrations (ordem de criação)

### 01 - modules

- id — bigIncrements
- name — string
- slug — string
- module_name — string
- endpoint_name — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 02 - statuses

- id — bigIncrements
- module_id — foreignId (fk modules)
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 03 - genders

- id — bigIncrements
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 04 - type_addresses

- id — bigIncrements
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 05 - addresses

- id — bigIncrements
- type_address_id — foreignId (fk type_addresses)
- addressable_type — string
- addressable_id — unsignedBigInteger
- zip_code — string
- street — string
- number — string nullable
- complement — string nullable
- neighborhood — string nullable
- city — string
- state — string
- country — string default 'Brasil'
- latitude — decimal 10,7 nullable
- longitude — decimal 10,7 nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (addressable_type, addressable_id)

### 06 - type_contacts

- id — bigIncrements
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 07 - contacts

- id — bigIncrements
- type_contact_id — foreignId (fk type_contacts)
- contactable_type — string
- contactable_id — unsignedBigInteger
- value — string
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (contactable_type, contactable_id)

### 08 - type_documents

- id — bigIncrements
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 09 - documents

- id — bigIncrements
- type_document_id — foreignId (fk type_documents)
- documentable_type — string
- documentable_id — unsignedBigInteger
- value — string
- expires_at — date nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (documentable_type, documentable_id)

### 10 - files

- id — bigIncrements
- fileable_type — string
- fileable_id — unsignedBigInteger
- name — string
- path — string
- mime_type — string
- size — unsignedBigInteger
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (fileable_type, fileable_id)

### 11 - notes

- id — bigIncrements
- noteable_type — string
- noteable_id — unsignedBigInteger
- content — text
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (noteable_type, noteable_id)

### 12 - finance_types

- id — bigIncrements
- name — string
- slug — string
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 13 - payment_methods

- id — bigIncrements
- name — string
- slug — string
- fee_percentage — decimal 5,2
- fee_fixed — decimal 10,2
- anticipation_fee_percentage — decimal 5,2
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 14 - plans

- id — bigIncrements
- status_id — foreignId (fk statuses)
- name — string
- slug — string
- max_people — integer
- monthly_price — decimal 10,2
- setup_price — decimal 10,2
- discount_percentage — decimal 5,2
- discount_value — decimal 10,2
- order — integer default 0
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 15 - tenants

- id — string (PK)
- plan_id — foreignId (fk plans nullable)
- name — string
- slug — string
- data — json nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 16 - domains

- id — bigIncrements
- tenant_id — string (fk tenants)
- domain — string
- created_at — timestamp
- updated_at — timestamp

### 17 - people

- id — bigIncrements
- tenant_id — string (fk tenants nullable)
- gender_id — foreignId (fk genders)
- name — string
- birth_date — date nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

tenant_id preenchido → responsável/admin da igreja
tenant_id null → admin da plataforma Bethel360°

### 18 - users

- id — bigIncrements
- person_id — foreignId (fk people)
- email — string unique
- password — string
- email_verified_at — timestamp nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 19 - finances

- id — bigIncrements
- parent_id — foreignId (fk finances nullable)
- person_id — foreignId (fk people nullable)
- finance_type_id — foreignId (fk finance_types)
- amount — decimal 10,2
- discount — decimal 10,2 nullable
- interest — decimal 10,2 nullable
- net_amount — decimal 10,2 nullable
- installment — integer nullable
- payment_method_id — foreignId (fk payment_methods)
- status_id — foreignId (fk statuses)
- external_id — string nullable
- paid_at — timestamp nullable
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

parent_id permite:
- Agrupar: 2 títulos → 1 novo (filhos apontam pro pai)
- Parcelar: 1 título → 10 parcelas (parcelas apontam pro original)

### 20 - permissions

- id — bigIncrements
- module_id — foreignId (fk modules)
- action — string (view, create, update, delete)
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 20b - person_permissions (pivot)

- id — bigIncrements
- person_id — foreignId (fk people)
- permission_id — foreignId (fk permissions)
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable

### 21 - audit_logs

- id — bigIncrements
- person_id — foreignId (fk people nullable)
- action — enum (created, updated, deleted)
- model — string
- model_id — unsignedBigInteger
- changes — json nullable
- ip — string nullable
- response — integer
- created_at — timestamp
- SEM updated_at, SEM soft_deletes — permanente

---

## Models (banco central)

Todos herdam BaseModel (SoftDeletes + HasAudit), exceto User e AuditLog.
Todos os models centrais usam: `protected $connection = 'central';`

### Module
- hasMany Status

### Status
- belongsTo Module

### Gender
- (sem relacionamentos extras)

### TypeAddress
- hasMany Address

### Address
- belongsTo TypeAddress
- morphTo addressable

### TypeContact
- hasMany Contact

### Contact
- belongsTo TypeContact
- morphTo contactable

### TypeDocument
- hasMany Document

### Document
- belongsTo TypeDocument
- morphTo documentable

### File
- morphTo fileable

### Note
- morphTo noteable

### FinanceType
- (sem relacionamentos extras)

### PaymentMethod
- (sem relacionamentos extras)

### Plan
- belongsTo Status
- hasMany Tenant

### Tenant
- belongsTo Plan (nullable)
- hasMany Domain
- hasMany Person
- morphMany Address, Contact, Document, Note, File

### Domain
- belongsTo Tenant

### Person
- belongsTo Tenant (nullable)
- belongsTo Gender
- hasOne User
- hasMany Finance
- hasMany PersonPermission
- morphMany Address, Contact, Document, Note, File

### User
- belongsTo Person
- NÃO herda BaseModel, herda Authenticatable
- usa SoftDeletes

### Finance
- belongsTo Finance (parent, nullable)
- hasMany Finance (children)
- belongsTo Person (nullable)
- belongsTo FinanceType
- belongsTo PaymentMethod
- belongsTo Status

### Permission
- belongsTo Module
- hasMany PersonPermission

### PersonPermission (pivot)
- belongsTo Person
- belongsTo Permission

### AuditLog
- belongsTo Person (nullable)
- Sem SoftDeletes, permanente, sem updated_at

---

## Seeders (banco central)

### ModuleSeeder
- Planos (slug: planos, module_name: plans, endpoint_name: plans)
- Tenants (slug: tenants, module_name: tenants, endpoint_name: tenants)
- Financeiro (slug: financeiro, module_name: finances, endpoint_name: finances)
- Pessoas (slug: pessoas, module_name: people, endpoint_name: people)
- Permissões (slug: permissoes, module_name: permissions, endpoint_name: permissions)

### StatusSeeder
- planos: ativo, inativo
- tenants: ativo, inativo, suspenso
- finances: pendente, confirmado, cancelado

### GenderSeeder
- Masculino, Feminino

### TypeAddressSeeder
- Residencial, Comercial

### TypeContactSeeder
- E-mail, Telefone, WhatsApp, Instagram

### TypeDocumentSeeder
- CPF, RG, CNPJ

### FinanceTypeSeeder
- Entrada (income), Saída (expense)

### PaymentMethodSeeder
- PIX (fee: 0%, fee_fixed: 0, anticipation: 0%)
- Cartão de Crédito (fee: 2%, fee_fixed: 0, anticipation: 5%)
- Cartão de Débito (fee: 1.5%, fee_fixed: 0, anticipation: 0%)
- Boleto (fee: 0%, fee_fixed: 5.00, anticipation: 0%)

### PlanSeeder
- Plano 500 (max_people: 500, monthly_price: 1140.00, setup_price: 2335.00)
- Plano 1000 (max_people: 1000, monthly_price: 1490.00, setup_price: 4590.00)
- Plano 2000 (max_people: 2000, monthly_price: 2190.00, setup_price: 9100.00)
- Plano 3000 (max_people: 3000, monthly_price: 2790.00, setup_price: 13070.00)
- Plano 4000 (max_people: 4000, monthly_price: 3290.00, setup_price: 17000.00)
- Plano 5000 (max_people: 5000, monthly_price: 3790.00, setup_price: 21030.00)
- Plano 10000+ (max_people: 10000+, monthly_price: 0.00 (sob consulta), setup_price: 0.00 (sob consulta))

---

## Mudança no Banco do Tenant

### notes — remover person_id

A tabela `notes` no banco do tenant deve ter o campo `person_id` **removido**. A informação de quem criou a nota já está no `audit_logs`.

Estrutura atualizada de notes (tenant):

- id — bigIncrements
- noteable_type — string
- noteable_id — unsignedBigInteger
- content — text
- created_at — timestamp
- updated_at — timestamp
- deleted_at — timestamp nullable
- INDEX em (noteable_type, noteable_id)

---

## Acesso Granular

O sistema de permissões é baseado em **módulo + ação**. Não existe "super admin" — cada pessoa recebe permissões individuais.

Tela mostra como checklist:

| Módulo | Ver | Criar | Editar | Excluir |
|--------|-----|-------|--------|---------|
| Tenants | ☑ | ☑ | ☑ | ☐ |
| Planos | ☑ | ☐ | ☐ | ☐ |
| Financeiro | ☑ | ☑ | ☑ | ☑ |
| Pessoas | ☑ | ☑ | ☐ | ☐ |
| Permissões | ☑ | ☑ | ☑ | ☑ |

---

## Prompt para Claude Code

```
Atualize o projeto bethel360-api para incluir o banco central completo:

## 1. Migrations do Banco Central
As migrations centrais ficam em database/migrations/ (NÃO em tenant/).
Crie na seguinte ordem, todas com soft_deletes e timestamps (exceto audit_logs e domains):

01 - modules: id, name (string), slug (string), module_name (string), endpoint_name (string), order (integer default 0), timestamps, soft_deletes

02 - statuses: id, module_id (fk modules), name (string), slug (string), order (integer default 0), timestamps, soft_deletes

03 - genders: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

04 - type_addresses: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

05 - addresses: id, type_address_id (fk type_addresses), addressable_type (string), addressable_id (unsignedBigInteger), zip_code (string), street (string), number (string nullable), complement (string nullable), neighborhood (string nullable), city (string), state (string), country (string default 'Brasil'), latitude (decimal 10,7 nullable), longitude (decimal 10,7 nullable), timestamps, soft_deletes — INDEX em (addressable_type, addressable_id)

06 - type_contacts: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

07 - contacts: id, type_contact_id (fk type_contacts), contactable_type (string), contactable_id (unsignedBigInteger), value (string), timestamps, soft_deletes — INDEX em (contactable_type, contactable_id)

08 - type_documents: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

09 - documents: id, type_document_id (fk type_documents), documentable_type (string), documentable_id (unsignedBigInteger), value (string), expires_at (date nullable), timestamps, soft_deletes — INDEX em (documentable_type, documentable_id)

10 - files: id, fileable_type (string), fileable_id (unsignedBigInteger), name (string), path (string), mime_type (string), size (unsignedBigInteger), timestamps, soft_deletes — INDEX em (fileable_type, fileable_id)

11 - notes: id, noteable_type (string), noteable_id (unsignedBigInteger), content (text), timestamps, soft_deletes — INDEX em (noteable_type, noteable_id). SEM person_id.

12 - finance_types: id, name (string), slug (string), order (integer default 0), timestamps, soft_deletes

13 - payment_methods: id, name (string), slug (string), fee_percentage (decimal 5,2), fee_fixed (decimal 10,2), anticipation_fee_percentage (decimal 5,2), order (integer default 0), timestamps, soft_deletes

14 - plans: id, status_id (fk statuses), name (string), slug (string), max_people (integer), monthly_price (decimal 10,2), setup_price (decimal 10,2), discount_percentage (decimal 5,2), discount_value (decimal 10,2), order (integer default 0), timestamps, soft_deletes

15 - tenants: id (string PK), plan_id (fk plans nullable), name (string), slug (string), data (json nullable), timestamps, soft_deletes

16 - domains: id, tenant_id (string fk tenants), domain (string), timestamps

17 - people: id, tenant_id (string fk tenants nullable), gender_id (fk genders), name (string), birth_date (date nullable), timestamps, soft_deletes

18 - users: id, person_id (fk people), email (string unique), password (string), email_verified_at (timestamp nullable), timestamps, soft_deletes

19 - finances: id, parent_id (fk finances nullable), person_id (fk people nullable), finance_type_id (fk finance_types), amount (decimal 10,2), discount (decimal 10,2 nullable), interest (decimal 10,2 nullable), net_amount (decimal 10,2 nullable), installment (integer nullable), payment_method_id (fk payment_methods), status_id (fk statuses), external_id (string nullable), paid_at (timestamp nullable), timestamps, soft_deletes

20 - permissions: id, module_id (fk modules), action (string), timestamps, soft_deletes

20b - person_permissions: id, person_id (fk people), permission_id (fk permissions), timestamps, soft_deletes

21 - audit_logs: id, person_id (fk people nullable), action (enum: created, updated, deleted), model (string), model_id (unsignedBigInteger), changes (json nullable), ip (string nullable), response (integer), created_at (timestamp) — SEM updated_at, SEM soft_deletes

IMPORTANTE: Estas migrations rodam com php artisan migrate (banco central). As migrations do tenant continuam em database/migrations/tenant/ e rodam com php artisan tenants:migrate.

## 2. Models do Banco Central
Crie os models em app/Models/Central/ para diferenciar dos models do tenant.
Todos usam: protected $connection = 'central';

- Central/Module (hasMany Status)
- Central/Status (belongsTo Module)
- Central/Gender
- Central/TypeAddress (hasMany Address)
- Central/Address (belongsTo TypeAddress, morphTo addressable)
- Central/TypeContact (hasMany Contact)
- Central/Contact (belongsTo TypeContact, morphTo contactable)
- Central/TypeDocument (hasMany Document)
- Central/Document (belongsTo TypeDocument, morphTo documentable)
- Central/File (morphTo fileable)
- Central/Note (morphTo noteable)
- Central/FinanceType
- Central/PaymentMethod
- Central/Plan (belongsTo Status, hasMany Tenant)
- Central/Tenant (belongsTo Plan nullable, hasMany Domain, hasMany Person, morphMany Address/Contact/Document/Note/File)
- Central/Domain (belongsTo Tenant)
- Central/Person (belongsTo Tenant nullable, belongsTo Gender, hasOne User, hasMany Finance, hasMany PersonPermission, morphMany Address/Contact/Document/Note/File)
- Central/User (belongsTo Person) — herda Authenticatable, usa SoftDeletes
- Central/Finance (belongsTo Finance parent nullable, hasMany Finance children, belongsTo Person nullable, belongsTo FinanceType, belongsTo PaymentMethod, belongsTo Status)
- Central/Permission (belongsTo Module, hasMany PersonPermission)
- Central/PersonPermission (belongsTo Person, belongsTo Permission)
- Central/AuditLog (belongsTo Person nullable) — sem SoftDeletes, permanente

## 3. Seeders do Banco Central
Crie CentralSeeder que roda no banco central:

### ModuleSeeder
- Planos (slug: planos, module_name: plans, endpoint_name: plans)
- Tenants (slug: tenants, module_name: tenants, endpoint_name: tenants)
- Financeiro (slug: financeiro, module_name: finances, endpoint_name: finances)
- Pessoas (slug: pessoas, module_name: people, endpoint_name: people)
- Permissões (slug: permissoes, module_name: permissions, endpoint_name: permissions)

### StatusSeeder
- planos: ativo, inativo
- tenants: ativo, inativo, suspenso
- finances: pendente, confirmado, cancelado

### GenderSeeder
- Masculino, Feminino

### TypeAddressSeeder
- Residencial, Comercial

### TypeContactSeeder
- E-mail, Telefone, WhatsApp, Instagram

### TypeDocumentSeeder
- CPF, RG, CNPJ

### FinanceTypeSeeder
- Entrada (income), Saída (expense)

### PaymentMethodSeeder
- PIX (fee_percentage: 0, fee_fixed: 0, anticipation_fee_percentage: 0)
- Cartão de Crédito (fee_percentage: 2.00, fee_fixed: 0, anticipation_fee_percentage: 5.00)
- Cartão de Débito (fee_percentage: 1.50, fee_fixed: 0, anticipation_fee_percentage: 0)
- Boleto (fee_percentage: 0, fee_fixed: 5.00, anticipation_fee_percentage: 0)

### PlanSeeder
- Plano 500 (max_people: 500, monthly_price: 1490.00, setup_price: 4590.00)

### PermissionSeeder
Cria permissões para cada módulo com as 4 ações (view, create, update, delete):
- planos: view, create, update, delete
- tenants: view, create, update, delete
- financeiro: view, create, update, delete
- pessoas: view, create, update, delete
- permissoes: view, create, update, delete

## 4. Correção no Banco do Tenant
Crie uma migration no tenant para REMOVER o campo person_id da tabela notes:

```php
Schema::table('notes', function (Blueprint $table) {
    $table->dropForeign(['person_id']);
    $table->dropColumn('person_id');
});
```

## 5. Configuração de Conexão
No config/database.php, garanta que existem duas conexões:
- 'central' → aponta pro bethel360_central
- 'tenant' → conexão dinâmica do Stancl

O Stancl deve usar 'central' como conexão principal e trocar pra 'tenant' ao resolver o tenant.

## 6. Auth Web para Banco Central
Crie controllers web para autenticação no banco central:
- app/Http/Controllers/Central/AuthController.php (login, logout)
- Rotas em routes/web.php (NÃO em tenant.php)
- Login autentica contra Central/User
- Após login, checa person_permissions para definir o que o usuário pode acessar

Rode: php artisan migrate (central) e garanta que todas as tabelas são criadas corretamente.
Rode os seeders e garanta que os dados base são populados.
```
