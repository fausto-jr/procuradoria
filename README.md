# Sistema de Controle de Pareceres Jurídicos

Sistema web desenvolvido em PHP para controle e gerenciamento de pareceres jurídicos, permitindo o acompanhamento completo do fluxo de análise de processos.

## 📋 Funcionalidades

### 🔐 Autenticação e Autorização
- Sistema de login seguro
- Controle de acesso baseado em perfis (Admin/Usuário)
- Sessões seguras com validação

### 📄 Gestão de Pareceres
- **Cadastro de Pareceres**: Registro completo com protocolo, assunto, interessado, tipo e relator
- **Controle de Status**: Acompanhamento do fluxo (Em Análise, Concluído, Pendente)
- **Controle de Prazos**: Cálculo automático de dias em atraso
- **Filtros Avançados**: Busca por status, tipo, processo, assunto, interessado, relator e período
- **Paginação Inteligente**: Navegação otimizada com preservação de filtros

### 📊 Relatórios e Dashboard
- **Dashboard Executivo**: Visão geral com estatísticas e gráficos
- **Relatórios Detalhados**: Análise por período, status e relator
- **Exportação**: Geração de relatórios em Excel e PDF
- **Estatísticas em Tempo Real**: Totais por status e performance

### 👥 Gestão de Usuários
- Cadastro e edição de usuários
- Controle de perfis e permissões
- Gestão de relatores

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Bibliotecas**: 
  - PhpSpreadsheet (exportação Excel)
  - TCPDF (geração PDF)
  - Chart.js (gráficos)
- **Servidor Web**: Apache/Nginx

## 📁 Estrutura do Projeto

```
procuradoria/
├── config/
│   └── database.php          # Configurações do banco de dados
├── database/
│   └── schema.sql            # Estrutura do banco de dados
├── src/
│   ├── controllers/          # Controladores MVC
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── ParecerController.php
│   │   ├── RelatorioController.php
│   │   └── UserController.php
│   ├── helpers/              # Funções auxiliares
│   │   ├── auth_helper.php
│   │   ├── flash_helper.php
│   │   ├── pagination_helper.php
│   │   └── parecer_helper.php
│   └── views/                # Templates e views
│       ├── auth/
│       ├── dashboard/
│       ├── layouts/
│       ├── pareceres/
│       ├── relatorios/
│       └── usuarios/
├── index.php                 # Ponto de entrada da aplicação
└── README.md
```

## ⚙️ Instalação e Configuração

### Pré-requisitos
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/Nginx)
- Composer (opcional, para dependências futuras)

### Passo a Passo

1. **Clone o repositório**
   ```bash
   git clone https://github.com/fausto-jr/procuradoria.git
   cd procuradoria
   ```

2. **Configure o banco de dados**
   - Crie um banco de dados MySQL
   - Execute o script `database/schema.sql`
   - Configure as credenciais em `config/database.php`

3. **Configuração do banco**
   ```php
   // config/database.php
   return [
       'host' => 'localhost',
       'dbname' => 'procuradoria',
       'username' => 'seu_usuario',
       'password' => 'sua_senha',
       'charset' => 'utf8mb4'
   ];
   ```

4. **Configure o servidor web**
   - Aponte o DocumentRoot para a pasta do projeto
   - Ou use o servidor embutido do PHP:
   ```bash
   php -S localhost:8080
   ```

5. **Acesse o sistema**
   - URL: `http://localhost:8080`
   - Usuário padrão será criado conforme configuração inicial

## 🚀 Uso do Sistema

### Login
Acesse o sistema através da tela de login com suas credenciais.

### Dashboard
- Visualize estatísticas gerais
- Acesse gráficos de performance
- Navegue pelos módulos principais

### Pareceres
- **Listar**: Visualize todos os pareceres com filtros avançados
- **Cadastrar**: Registre novos pareceres com todas as informações
- **Editar**: Atualize dados e status dos pareceres
- **Visualizar**: Consulte detalhes completos

### Relatórios
- Gere relatórios por período
- Filtre por status, tipo e relator
- Exporte em Excel ou PDF
- Visualize estatísticas detalhadas

## 🔧 Funcionalidades Técnicas

### Paginação Inteligente
- Sistema de paginação otimizado
- Preservação de filtros durante navegação
- Exibição de informações contextuais

### Segurança
- Validação de entrada de dados
- Proteção contra SQL Injection
- Controle de sessões seguras
- Sanitização de dados

### Performance
- Consultas otimizadas
- Cache de sessões
- Paginação eficiente
- Índices de banco otimizados

## 📈 Melhorias Implementadas

### Paginação Avançada
- Implementação de paginação inteligente nas views de pareceres e relatórios
- Helper de paginação reutilizável (`pagination_helper.php`)
- Preservação automática de filtros durante navegação
- Informações contextuais de paginação

### Otimizações de Performance
- Consultas SQL otimizadas com LIMIT e OFFSET
- Cálculo eficiente de totais
- Redução de consultas desnecessárias

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👨‍💻 Autor

**Fausto Jr**
- GitHub: [@fausto-jr](https://github.com/fausto-jr)

## 📞 Suporte

Para suporte e dúvidas, abra uma issue no GitHub ou entre em contato através do email.

---

⭐ Se este projeto foi útil para você, considere dar uma estrela no GitHub!