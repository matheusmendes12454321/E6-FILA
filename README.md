# 🚦 E6-FILA — Sistema de Fila e Chamada Acessível para Atendimento

> **Projeto Integrador (2026.2)** — IFNMG Campus Almenara  
> **Código do Dispositivo:** `E6-FILA`  
> **Área de Integração:** Administração, Serviços, Inclusão, Estatística e Experiência do Usuário.

---

## 📌 Sobre o Projeto

O **E6-FILA** é um sistema integrado de hardware, firmware e software projetado para automatizar e humanizar o atendimento ao público. O sistema permite a emissão física de senhas (comuns e prioritárias), o gerenciamento do fluxo de atendimento por meio de um painel web intuitivo e a sinalização visual e sonora acessível no ponto de atendimento.

---

## 👥 Integrantes da Equipe (Equipe 06)

* **Matheus** — Arquitetura de Software, API Backend (Laravel) e Banco de Dados.
* **Pethrus** — Suporte de Software, Firmware ESP32 e Interface Frontend.
* **Kauã** — Projeto do Circuito Eletroeletrônico, Pinagem e Protótipo Físico.
* **Jonas** — Construção da Maquete, Acabamento e Acessibilidade Física.
* **Madalena** — Garantia de Qualidade (QA), Planos de Teste, UX e Documentação.

---

## 🏗️ Arquitetura do Sistema

O sistema opera com fluxo de comunicação bidirecional entre o hardware (ESP32) e o backend (Laravel):

1. **Emissão de Senha:** Botões físicos na protoboard/maquete enviam requisições `POST` HTTP para o servidor Laravel via Wi-Fi.
2. **Gerenciamento:** O atendente interage com o Painel Laravel para chamar, pausar ou finalizar senhas.
3. **Chamada Visual/Sonora:** O ESP32 realiza *polling* periódico na API, recebe novos comandos, exibe a senha no Display OLED e aciona o Buzzer e LEDs de sinalização.

```text
 [Botões Físicos] ──(HTTP POST)──> [Laravel Backend / API] <─── [Painel do Atendente]
                                            │
 [Display OLED + Buzzer] <──(HTTP GET)──────┴─────────────────> [Painel Público / TV]
