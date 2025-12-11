# CinemaCity — Project Structure (Policy)

Este ficheiro documenta a estrutura obrigatória do projeto e as regras a seguir. O objetivo é garantir que a organização de pastas e ficheiros se mantenha conforme exigido.

## Regras obrigatórias
- Não mover, renomear ou apagar pastas top-level sem autorização explícita do proprietário do projeto.
- Pastas top-level (ex.: `assets/`, `templates/`, `api/`, `clients/`, `products/`, `blog/`, `layouts/`, `pages/`, `partials/`) devem ser preservadas na sua posição atual.
- É permitido editar conteúdo dentro de ficheiros (HTML/CSS/JS), corrigir caminhos relativos e adicionar novos ficheiros, desde que a estrutura base não seja alterada.
- NÃO executar alterações destrutivas (remoção/renomeação de ficheiros existentes em `assets/images`, `templates`, etc.) sem aprovação prévia.

## Estrutura (resumo)
- `assets/` — CSS, JS, imagens e outros assets estáticos.
  - `assets/css/` — folhas de estilo (ex.: `styles.css`, `movie.css`).
  - `assets/images/` — imagens usadas no site (a tua versão usa `assets/images/gallery/` para posters e banners).
  - `assets/js/` — scripts do site.
- `templates/` — templates e páginas mustache (se aplicável).
- `api/` — scripts do servidor (PHP) — manter, não remover sem concordância.
- `clients/`, `blog/`, `products/`, `about/` — páginas estáticas e conteúdos relacionados.

## Convenções e boas práticas
- Evitar espaços nos nomes de ficheiros (usar `capa-2.jpg` em vez de `capa 2.jpg`). Se quiseres renomear ficheiros, pede autorização: posso gerar um plano e backup antes.
- Sempre usar caminhos relativos corretos nos CSS (`../images/...` quando o CSS está em `assets/css/`).
- Antes de alterações em massa, criar um backup ZIP do diretório do projeto.

## Operações autorizadas sem pedido prévio
- Corrigir referências quebradas em HTML/CSS/JS (apenas atualizando caminhos), adicionar novos ficheiros estáticos.

## Operações proibidas sem autorização
- Mover ou remover pastas `assets/`, `templates/`, `api/`, `clients/`, `products/` e conteúdos críticos.
- Renomear imagens, PHPs ou ficheiros de template sem aprovação.

## Processo para mudanças significativas
1. Criar backup (zip) do projeto.
2. Documentar as mudanças propostas (patches/renomeações).
3. Pedir aprovação do proprietário.
4. Aplicar alterações e preparar commit local (não faço push sem autorização).

---
Se concordas com este ficheiro, diz que posso gravá-lo no repositório (já o criei localmente). Posso também criar o backup ZIP quando autorizares.
