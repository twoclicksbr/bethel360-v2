# Bethel360° — Documento de Estrutura

---

## Plataforma e Isolamento

O Bethel360° é a plataforma central que armazena todas as informações. É o **motor invisível** por trás de cada igreja.

### Um Banco de Dados por Tenant

Isolamento no nível mais forte possível. Não é schema separado dentro do mesmo banco — é um **banco de dados inteiro por igreja**.

- Segurança total entre igrejas (zero risco de vazamento)
- Performance independente (uma igreja grande não impacta uma pequena)
- Backup e restauração individual por tenant
- Se uma igreja sai do Bethel360°, os dados estão isolados — entrega ou apaga sem afetar ninguém

---

## Acesso

### Subdomínio Padrão

Cada tenant acessa pelo seu subdomínio:

```
{tenant.slug}.bethel360.com.br
```

O slug identifica o tenant, o sistema conecta ao banco correto e a igreja cai no seu universo isolado.

### Domínio Próprio (White-label)

A igreja pode apontar seu domínio para o subdomínio do Bethel360°:

```
igrejadacidade.net.br → igrejadacidade.bethel360.com.br
```

A marca Bethel360° desaparece completamente. Para quem acessa, o sistema é da igreja. White-label completo.

---

## API-First

Tudo no Bethel360° é uma **API REST em JSON**. Ninguém acessa o banco direto — tudo passa pela API.

### Quem consome a API:

- **Painel web** — onde líder e admin gerenciam tudo
- **App** — onde membro e líder acessam pelo celular (app único, permissão define o que cada pessoa vê)
- **Sites institucionais** — pages públicas e restritas
- **Google Workspace** — Meet, Drive, Calendar por baixo
- **Integrações externas** — WhatsApp, Asaas, e o que mais vier
- **Igrejas** — podem conectar seus próprios sistemas via API REST

---

## Google Workspace

### Infraestrutura Invisível

O Bethel360° **fornece** o Google Workspace para a igreja. Não é uma integração com o Workspace da igreja — é o Workspace do Bethel360° que roda por baixo.

A igreja contrata o Bethel360° e já recebe tudo junto.

### O que está incluso no pacote:

| Serviço | Uso dentro do Bethel360° |
|---------|--------------------------|
| **Google Meet** | Salas de reunião criadas automaticamente pelo sistema |
| **Google Drive** | Armazenamento em nuvem (até 2TB) |
| **Google Calendar** | Agenda integrada com push de notificações |

**E-mail NÃO faz parte do pacote.**

### Camada Invisível

A igreja **não acessa** o Google Drive, Calendar ou Meet diretamente. Ela usa o Bethel360° e por trás a plataforma usa esses serviços:

- O líder cancela um evento dentro do Bethel360° → o sistema atualiza no Calendar → o Calendar dispara o push para todos
- Arquivos de um ministério são acessados pelo Bethel360° → por trás o Drive armazena
- Reunião online é criada dentro do Bethel360° → por trás é Meet → para quem clica é só um botão na plataforma

O Google Workspace é o motor escondido dentro do motor escondido. O Bethel360° já é invisível para a igreja. O Google é invisível até para o Bethel360°, do ponto de vista do usuário.

---

## Sites Institucionais (Pages)

Cada tenant, campus, ministério e grupo pode ter um site institucional. O layout é padrão do Bethel360°, rico e alimentado automaticamente pelo banco de dados.

### Estrutura de Rotas

As rotas seguem a hierarquia da plataforma:

| Rota | Nível | O que exibe |
|------|-------|-------------|
| `/page/home` | Tenant | Site da igreja |
| `/page/betania` | Campus | Site do campus Betânia |
| `/page/colina/rota-do-conhecimento` | Ministério | Site do ministério dentro do campus |
| `/page/colina/rota-do-conhecimento/uma-vida-com-proposito` | Grupo | Site de um grupo específico |

### Page Pública vs Page Restrita

**Page pública** — `/page/30semanas/lutos-e-perdas`

Qualquer pessoa acessa, sem login. É a vitrine. Mostra informações institucionais — o que é o grupo, como funciona, horários, fotos, botão de inscrição. É o que se compartilha no Instagram, WhatsApp, para atrair pessoas.

**Page restrita** — `/page/auth/30semanas/lutos-e-perdas`

Só quem está autenticado e tem permissão acessa. Conteúdo exclusivo dos participantes, materiais, informações sensíveis, acompanhamento.

O `/auth/` na rota é o divisor de águas. Sem ele, é público. Com ele, o sistema valida quem é a pessoa e se ela tem vínculo ativo naquele grupo.

### Page Restrita Dinâmica e Personalizada

Quando a pessoa autenticada acessa `/page/auth/30semanas`, a página é **personalizada automaticamente** com base no perfil dela.

O sistema cruza o perfil da pessoa (gênero, idade, status, pré-requisitos concluídos) com as **ministry_group_features** de cada grupo e mostra **só o que é compatível**:

- Mulher de 18+ → vê grupos femininos e mistos de 18+
- Homem de 16 → vê grupos masculinos e mistos de 15-17
- Pastor → vê o grupo exclusivo de pastores

Cada pessoa entra na mesma rota e vê uma página diferente. Grupos lotados (que atingiram a capacidade) ficam indisponíveis.

### Visibilidade e Sigilo

Cada page pode ser:

- **Pública** — acessível sem login
- **Restrita** — acessível só com autenticação e vínculo
- **Ambas** — versão pública para divulgação e versão restrita para participantes

Grupos sigilosos (ex: grupo de pastores sobre sexualidade) podem existir **apenas na rota restrita**, sem page pública. Ninguém de fora sabe que existe.

### Criação de Pages

Ao criar uma page, o sistema pergunta:

1. **Tem vínculo?** (campus, ministério, grupo ou página avulsa)
2. **Quer usar a página padrão ou customizar?**

**Page com vínculo** → puxa dados dinâmicos do ministério (eventos, agenda, inscrições, conteúdo vivo). A igreja não precisa fazer nada — criou o ministério, a página já nasce com conteúdo.

**Page sem vínculo** → conteúdo estático institucional apenas (quem somos, doações, comunicados). Não tem agenda, não tem eventos, porque **não existe atividade sem ministério**.

**Page customizada** → a igreja tem liberdade para montar do jeito dela, mas dentro do ecossistema Bethel360°.

---

## Reuniões Online (Meet)

### Sala Criada pelo Bethel360°

O Bethel360° é o dono da sala. Ele cria, ele controla. Quando o líder acessa a page restrita do seu grupo e clica em "entrar na sala", o sistema:

1. Reconhece que é líder daquele grupo
2. Cria a sala automaticamente via Google Meet
3. O líder entra como **co-host**

### Sala Dentro do Bethel360°

A sala do Meet roda **dentro** do Bethel360° (iframe/embed). Ninguém sai da plataforma. A partilha acontece ali mesmo, sem copiar link, sem trocar de aba.

### Co-host do Líder

O líder precisa ter poder dentro da sala:
- Aceitar pessoas na porta
- Mutar microfones
- Remover uma pessoa da sala

**Requisito técnico pendente:** a API do Google Meet ainda precisa ser explorada para garantir permissões de co-host. A documentação do Meet é limitada nesse ponto.

### Presença por Tempo de Permanência

Com o Meet integrado, o Bethel360° sabe **quanto tempo** cada pessoa ficou na sala. A presença só conta se a pessoa permaneceu pelo menos um tempo mínimo. Isso resolve o "golpe da presença" — entrar, marcar e sair.

---

## Templates de Ministério (Estrutura)

O template define o **tipo** do ministério. A arquitetura já nasce preparada para templates, mas inicialmente apenas o Padrão estará visível para o usuário.

### Templates Planejados

| Template | Grupos | Estrutura |
|----------|--------|-----------|
| **Padrão** | Criados livremente pela igreja | Flexível |
| **Loja Virtual** | Pré-definidos (ex: Catálogo, Pedidos, Estoque) | Semi-fixa |
| **Distribuição** | Pré-definidos (ex: Materiais, Igrejas Vinculadas) | Semi-fixa |
| **ERP** | Pré-definidos (ex: Compras, RH, Patrimônio, Financeiro, Manutenção) | Fixa |

No Padrão, a estrutura é livre. Nos demais, os grupos já nascem prontos ao ativar o template.

### Modelo de Negócio

- **Padrão** → incluso no plano base
- **Demais templates** → módulos premium, cobrados à parte

### Dúvida Técnica para Fase de Implementação

Cada template tem estruturas de dados muito diferentes. Investigar o uso de **schemas separados** no banco (core, erp, loja, distribuicao) para organizar as tabelas de cada template. O ministério vive no schema core, e ao ativar um template, o sistema conecta ele com as tabelas do schema correspondente.

---

## Modelo de Negócio

### Planos por Quantidade de Pessoas

| Faixa | Valor Mensal | Implantação |
|-------|-------------|-------------|
| 500 pessoas | R$ 1.490,00 | R$ 4.590,00 |
| 1.000 pessoas | A definir | A definir |
| 2.000 pessoas | A definir | A definir |
| 3.000 pessoas | A definir | A definir |
| 4.000 pessoas | A definir | A definir |
| 5.000 pessoas | A definir | A definir |
| 10.000+ pessoas | A definir | A definir |

### O que está incluso no plano:

- Acesso completo a todos os módulos (template Padrão)
- Aplicativo personalizado Android e iOS
- Site institucional integrado
- Suporte técnico humanizado
- Gestão Multicampus
- Treinamento completo
- Google Meet, Drive e Calendar (via Workspace do Bethel360°)

### Receitas Adicionais (Futuro)

- Templates premium (ERP, Loja Virtual, Distribuição)

---

## Hierarquia Completa — Exemplo Real

```
Igreja da Cidade (tenant)
│
├── Campus Colina (main)
│   ├── Ministério 30 Semanas
│   │   ├── Grupo Procrastinação (masc/fem) (18+) (online)
│   │   ├── Grupo Lutos e Perdas (fem) (15-18) (presencial)
│   │   ├── Grupo Sexualidade (pastores) (18+) (online) [sigiloso]
│   │   └── ...
│   ├── Ministério Louvor
│   ├── Ministério Rota do Conhecimento
│   │   ├── Grupo Uma Vida com Propósito (turma fechada)
│   │   └── ...
│   ├── Ministério de Celebrações
│   │   ├── Grupo Culto Domingo Manhã
│   │   ├── Grupo Culto Domingo Noite
│   │   └── Grupo Culto de Quarta
│   └── ...
│
├── Campus Betânia (filial)
│   ├── Ministério Louvor
│   └── ...
│
├── Campus Caraguá (filial)
│   ├── Ministério 30 Semanas
│   ├── Ministério Louvor
│   └── ...
│
├── [Futuro] Editora Inspire (ministério template ERP)
├── [Futuro] Rede Inspire (ministério template Distribuição)
├── [Futuro] Livraria Inspire (ministério template Loja Virtual)
└── [Futuro] Rádio, TV, Agência, etc.
```

---

## Resumo das Camadas de Invisibilidade

| Para quem usa | O que vê | O que está por trás |
|---------------|----------|---------------------|
| Membro da igreja | Site e app da igreja dele | Bethel360° (invisível) |
| Líder/Admin | Painel Bethel360° com marca da igreja | Google Workspace (invisível) |
| O Bethel360° | API e banco de dados | Meet, Drive, Calendar operando por baixo |

Camada sobre camada de white-label. Para o usuário final, tudo é simples.
