-- ============================================================
-- Animais de Rua — Dados de Teste
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- ============================================================
-- UTILIZADORES (voluntarios, loja, tradutores)
-- Os IDs 1 e 2 sao reservados ao seeder principal
-- ============================================================
INSERT INTO users (id, name, email, password, phone, status, notes, created_at, updated_at) VALUES
(10, 'Maria João Silva',    'mariaj.silva@animaisderua.org',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 912 345 678', 1, 'Voluntária desde 2019', NOW(), NOW()),
(11, 'Carlos Mendes',       'carlos.mendes@animaisderua.org',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 963 210 987', 1, 'Responsável pela loja online', NOW(), NOW()),
(12, 'Ana Beatriz Costa',   'ana.costa@animaisderua.org',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 936 789 012', 1, NULL, NOW(), NOW()),
(13, 'Filipa Rodrigues',    'filipa.rodrigues@animaisderua.org','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 911 456 789', 1, 'Tradutora EN/PT', NOW(), NOW()),
(14, 'Tiago Ferreira',      'tiago.ferreira@animaisderua.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 961 234 567', 1, NULL, NOW(), NOW()),
(15, 'Inês Carvalho',       'ines.carvalho@animaisderua.org',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 913 678 901', 1, 'Voluntária Porto', NOW(), NOW()),
(16, 'Bruno Sousa',         'bruno.sousa@animaisderua.org',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 962 345 678', 1, NULL, NOW(), NOW()),
(17, 'Catarina Nunes',      'catarina.nunes@animaisderua.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+351 914 901 234', 1, 'Loja Faro', NOW(), NOW());

-- Atribuicao de roles (model_type = Backpack user)
-- role 1=admin, 2=volunteer, 3=store, 4=translator, 5=friend card
INSERT INTO user_has_roles (role_id, model_type, model_id) VALUES
(2, 'Backpack\Base\app\Models\BackpackUser', 10),  -- volunteer
(3, 'Backpack\Base\app\Models\BackpackUser', 11),  -- store
(2, 'Backpack\Base\app\Models\BackpackUser', 12),  -- volunteer
(4, 'Backpack\Base\app\Models\BackpackUser', 13),  -- translator
(2, 'Backpack\Base\app\Models\BackpackUser', 14),  -- volunteer
(2, 'Backpack\Base\app\Models\BackpackUser', 15),  -- volunteer
(3, 'Backpack\Base\app\Models\BackpackUser', 16),  -- store
(3, 'Backpack\Base\app\Models\BackpackUser', 17);  -- store

-- Associar voluntarios a nucleos (HQ: 1=Porto, 2=Lisboa, 3=Sintra, 4=Faro, 5=São Miguel)
INSERT INTO users_headquarters (user_id, headquarter_id) VALUES
(10, 2), (12, 2), (13, 2), (14, 2),  -- Lisboa
(15, 1), (16, 1),                     -- Porto
(17, 4),                               -- Faro
(11, 2);                               -- Loja / Lisboa

-- ============================================================
-- MEDICOS VETERINARIOS
-- ============================================================
INSERT INTO vets (id, name, email, phone, address, url, status, created_at, updated_at) VALUES
(1, 'Clínica Veterinária Bom Pastor',    'bompastor@clinicaveterinaria.pt',  '+351 213 456 789', 'Rua do Bom Pastor, 12, 1200-043 Lisboa',   'www.clinicabompastor.pt',  'active', NOW(), NOW()),
(2, 'Hospital Veterinário do Porto',     'info@hvporto.pt',                  '+351 222 987 654', 'Avenida da Boavista, 1234, 4100-130 Porto', 'www.hvporto.pt',           'active', NOW(), NOW()),
(3, 'Centro Médico Veterinário de Faro', 'faro@cmvf.pt',                     '+351 289 765 432', 'Rua de Santo António, 45, 8000-284 Faro',  'www.cmvf.pt',              'active', NOW(), NOW()),
(4, 'Clínica Vet Sintra',               'sintra@clinicavet.pt',              '+351 219 234 567', 'Rua da Padaria, 7, 2710-558 Sintra',        NULL,                       'active', NOW(), NOW()),
(5, 'VetGaia — Clínica Veterinária',    'gaia@vetgaia.pt',                   '+351 223 112 233', 'Rua Padre Américo, 89, 4400-256 V. N. Gaia',NULL,                       'active', NOW(), NOW());

INSERT INTO vets_headquarters (vet_id, headquarter_id) VALUES
(1, 2), (1, 3),  -- Lisboa e Sintra
(2, 1),          -- Porto
(3, 4),          -- Faro
(4, 3),          -- Sintra
(5, 1);          -- Porto (Gaia)

-- ============================================================
-- RESPONSAVEIS DE COLONIA (FATs)
-- ============================================================
INSERT INTO fats (id, name, email, phone, territory_id, user_id, notes, created_at, updated_at) VALUES
(1, 'Rosa Margarida Pinto',  'rosa.pinto@gmail.com',       '+351 916 234 567', '1106', 10, 'Responsável por 3 colónias na Mouraria',           NOW(), NOW()),
(2, 'Henrique Azevedo',      'h.azevedo@sapo.pt',          '+351 963 456 789', '1312', 15, 'Gere colónias no bairro de Campanhã',              NOW(), NOW()),
(3, 'Lurdes Fonseca',        'lurdes.fonseca@gmail.com',   '+351 917 890 123', '1111', 12, 'Colónias em Queluz e arredores',                   NOW(), NOW()),
(4, 'Augusto Tavares',       'aug.tav@netcabo.pt',         '+351 289 345 678', '0805', 17, 'Responsável por 2 colónias em Faro',               NOW(), NOW()),
(5, 'Conceição Matos',       'cmatos@gmail.com',           '+351 912 567 890', '1106', 10, 'Colónia do Parque das Nações',                     NOW(), NOW()),
(6, 'Fernando Magalhães',    'fmagalhaes@outlook.pt',      '+351 964 678 901', '1312', 15, NULL,                                               NOW(), NOW());

INSERT INTO fats_headquarters (fat_id, headquarter_id) VALUES
(1, 2), (2, 1), (3, 3), (4, 4), (5, 2), (6, 1);

-- ============================================================
-- PADRINHOS
-- ============================================================
INSERT INTO godfathers (id, name, alias, email, phone, territory_id, user_id, notes, created_at, updated_at) VALUES
(1, 'Grupo BPI',             'BPI',       'mecenato@bpi.pt',              '+351 217 217 700', '1106', 1,  'Padrinho institucional desde 2018',     NOW(), NOW()),
(2, 'Leonor Vasconcellos',   'Leo',       'leonor.vasc@gmail.com',        '+351 913 456 789', '1106', 10, NULL,                                   NOW(), NOW()),
(3, 'Paulo Drummond',        'Drummond',  'paulo.drummond@hotmail.com',   '+351 964 789 012', '1312', 15, 'Padrinho de vários gatos',             NOW(), NOW()),
(4, 'Pet Shop Lisboa, Lda.', 'PetShop',   'geral@petshoplisboa.pt',       '+351 213 001 200', '1106', 1,  'Apoia com ração mensal',               NOW(), NOW()),
(5, 'Marta Olímpia Serrano', 'Marta',     'marta.serrano@gmail.com',      '+351 936 234 567', '1111', 12, NULL,                                   NOW(), NOW());

INSERT INTO godfathers_headquarters (godfather_id, headquarter_id) VALUES
(1, 2), (2, 2), (3, 1), (4, 2), (5, 3);

-- ============================================================
-- ADOTANTES
-- ============================================================
INSERT INTO adopters (id, name, email, phone, address, zip_code, id_card, territory_id, user_id, created_at, updated_at) VALUES
(1, 'Sofia Andrade',       'sofia.andrade@gmail.com',       '+351 916 789 012', 'Rua Almirante Reis, 120, 3.º Esq.',  '1150-023', '12345678',   '1106', 10, NOW(), NOW()),
(2, 'Ricardo Leitão',      'ricardo.leitao@hotmail.com',    '+351 965 012 345', 'Av. de Ceuta, 34, 1.º Dto.',          '1300-125', '23456789',   '1106', 10, NOW(), NOW()),
(3, 'Débora Esteves',      'debora.esteves@sapo.pt',        '+351 913 234 567', 'Rua das Flores, 8, R/C',              '4050-262', '34567890',   '1312', 15, NOW(), NOW()),
(4, 'Nuno Figueiredo',     'nuno.fig@gmail.com',            '+351 962 456 789', 'Rua Cândido dos Reis, 76, 2.º Esq.', '8000-145', '45678901',   '0805', 17, NOW(), NOW()),
(5, 'Vanessa Monteiro',    'vanessa.monteiro@gmail.com',    '+351 917 678 901', 'Rua Alexandre Herculano, 55, 4.º',   '2710-040', '56789012',   '1111', 12, NOW(), NOW());

-- ============================================================
-- PROTOCOLOS (parcerias com camaras municipais)
-- ============================================================
INSERT INTO protocols (id, name, email, phone, territory_id, headquarter_id, user_id, created_at, updated_at) VALUES
(1, 'Câmara Municipal de Lisboa',     'protocolo@cm-lisboa.pt',   '+351 213 230 200', '1106', 2, 1, NOW(), NOW()),
(2, 'Câmara Municipal do Porto',      'protocolo@cm-porto.pt',    '+351 222 097 000', '1312', 1, 1, NOW(), NOW()),
(3, 'Câmara Municipal de Faro',       'protocolo@cm-faro.pt',     '+351 289 800 800', '0805', 4, 1, NOW(), NOW());

-- ============================================================
-- PROCESSOS (colonias / grupos de animais)
-- ============================================================
INSERT INTO processes (id, name, contact, phone, email, address, territory_id, headquarter_id, user_id, specie, amount_males, amount_females, amount_other, status, urgent, history, notes, contacted, created_at, updated_at) VALUES
(1,  'Colónia da Mouraria',             'Rosa Pinto',       '+351 916 234 567', 'rosa.pinto@gmail.com',     'Rua da Mouraria, junto à fonte',         '1106', 2, 10, 'cat', 2, 5, 1, 'open',             0, 'Colónia estabelecida há mais de 10 anos. Animais identificados e esterilizados na maioria.', 'Abrigo existente precisa de reparação', 1, DATE_SUB(NOW(), INTERVAL 18 MONTH), NOW()),
(2,  'Colónia do Parque das Nações',    'Conceição Matos',  '+351 912 567 890', 'cmatos@gmail.com',         'Parque das Nações, junto ao lago',       '1106', 2, 10, 'cat', 3, 6, 0, 'open',             0, 'Colónia nova identificada em 2022. Gestão em curso.', NULL, 1, DATE_SUB(NOW(), INTERVAL 12 MONTH), NOW()),
(3,  'Cães da Rua da Boavista',         'Henrique Azevedo', '+351 963 456 789', 'h.azevedo@sapo.pt',        'Rua da Boavista, Porto',                 '1312', 1, 15, 'dog', 3, 1, 0, 'waiting_godfather', 1, 'Grupo de 4 cães identificados pela GNR local. Necessitam urgentemente de apadrinhamento.', 'Cão ferido na pata traseira', 1, DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(4,  'Gatos do Bairro de Belém',        'Maria João Silva', '+351 912 345 678', 'mariaj.silva@animaisderua.org', 'Rua Vieira Portuense, perto do jardim', '1106', 2, 10, 'cat', 1, 4, 0, 'open',             0, 'Colónia bem gerida. Todos os animais têm corte na orelha.', NULL, 1, DATE_SUB(NOW(), INTERVAL 24 MONTH), NOW()),
(5,  'Colónia de Queluz',               'Lurdes Fonseca',   '+351 917 890 123', 'lurdes.fonseca@gmail.com',  'Jardim do Palácio de Queluz',            '1111', 3, 12, 'cat', 2, 7, 1, 'open',             0, 'Colónia histórica associada ao jardim do palácio. Câmara colaborante.', 'Autorização da Parques de Sintra renovada em 2024', 1, DATE_SUB(NOW(), INTERVAL 30 MONTH), NOW()),
(6,  'Cão Abandonado — Faro Centro',    'Augusto Tavares',  '+351 289 345 678', 'aug.tav@netcabo.pt',        'Rua de Santo António, Faro',             '0805', 4, 17, 'dog', 1, 0, 0, 'waiting_capture',  1, 'Cão adulto de porte médio, pelagem castanha. Assustado mas sem comportamento agressivo.', 'Captura agendada para esta semana', 1, DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW()),
(7,  'Colónia Campanhã',                'Henrique Azevedo', '+351 963 456 789', 'h.azevedo@sapo.pt',        'Rua de Pinto Bessa, Campanhã',           '1312', 1, 15, 'cat', 0, 3, 0, 'open',             0, 'Três gatas, duas já esterilizadas.', NULL, 1, DATE_SUB(NOW(), INTERVAL 8 MONTH), NOW()),
(8,  'Cães Errantes — Sintra Vila',     'Lurdes Fonseca',   '+351 917 890 123', 'lurdes.fonseca@gmail.com',  'Volta do Duche, Sintra',                 '1111', 3, 12, 'dog', 2, 1, 0, 'approving',        0, 'Denúncia recebida via formulário do site. A verificar.', NULL, 0, DATE_SUB(NOW(), INTERVAL 2 WEEK), NOW()),
(9,  'Gatos do Intendente',             'Rosa Pinto',       '+351 916 234 567', 'rosa.pinto@gmail.com',     'Largo do Intendente, Lisboa',            '1106', 2, 10, 'cat', 1, 3, 0, 'open',             0, 'Colónia no largo histórico. Câmara de Lisboa colabora com abrigos.', 'Novo abrigo instalado em março 2025', 1, DATE_SUB(NOW(), INTERVAL 14 MONTH), NOW()),
(10, 'Cão Atropelado — N250',           'Fernando Magalhães','+351 964 678 901','fmagalhaes@outlook.pt',    'EN250, km 12, Maia',                     '1306', 1, 15, 'dog', 1, 0, 0, 'closed',           0, 'Animal resgatado após atropelamento. Tratado e adotado.', 'Processo encerrado após adoção bem-sucedida', 1, DATE_SUB(NOW(), INTERVAL 5 MONTH), NOW()),
(11, 'Colónia dos Restauradores',       'Conceição Matos',  '+351 912 567 890', 'cmatos@gmail.com',         'Praça dos Restauradores, Lisboa',        '1106', 2, 10, 'cat', 2, 4, 1, 'open',             0, 'Colónia muito visível em zona turística. Animais familiarizados com pessoas.', NULL, 1, DATE_SUB(NOW(), INTERVAL 20 MONTH), NOW()),
(12, 'Cadela Grávida — Matosinhos',     'Inês Carvalho',    '+351 913 678 901', 'ines.carvalho@animaisderua.org','Rua Brito Capelo, Matosinhos',      '1308', 1, 15, 'dog', 0, 1, 0, 'waiting_godfather', 1, 'Cadela grávida encontrada em estado de fraqueza. Internada na clínica parceira.', 'Urge apadrinhamento para cobrir custos veterinários', 1, DATE_SUB(NOW(), INTERVAL 3 WEEK), NOW()),
(13, 'Gatos da Calçada do Tojal',       'Maria João Silva', '+351 912 345 678', 'mariaj.silva@animaisderua.org','Calçada do Tojal, Lisboa',          '1114', 2, 10, 'cat', 0, 5, 0, 'open',             0, 'Cinco gatas identificadas. Processo de esterilização progressiva em curso.', NULL, 1, DATE_SUB(NOW(), INTERVAL 9 MONTH), NOW()),
(14, 'Processo Ilha — São Miguel',      NULL,               NULL,               NULL,                        'Lagoa, Ilha de São Miguel',              '21',   5, 14, 'dog', 4, 2, 1, 'open',             0, 'Colónia em zona rural da ilha. Coordenação com autarquia local.', 'Acesso difícil, requer coordenação com voluntária local', 1, DATE_SUB(NOW(), INTERVAL 36 MONTH), NOW()),
(15, 'Gatil Faro — Excesso de Gatos',   'Augusto Tavares',  '+351 289 345 678', 'aug.tav@netcabo.pt',        'Rua da Misericórdia, Faro',              '0805', 4, 17, 'cat', 3, 8, 2, 'archived',         0, 'Situação de excesso de felinos em habitação particular. Resolvida com esterilização massiva.', NULL, 1, DATE_SUB(NOW(), INTERVAL 48 MONTH), NOW());

-- ============================================================
-- CONSULTAS / AGENDAMENTOS
-- ============================================================
INSERT INTO appointments (id, process_id, user_id, vet_id_1, date_1, vet_id_2, date_2, amount_males, amount_females, amount_other, status, notes, notes_deliver, notes_collect, created_at, updated_at) VALUES
(1,  1,  10, 1, DATE_SUB(NOW(), INTERVAL 10 MONTH), NULL, NULL, 1, 2, 0, 'approved_option_1', 'Esterilização de 3 animais',         'Entrega às 8h na clínica',      'Recolha às 18h',          DATE_SUB(NOW(), INTERVAL 10 MONTH), NOW()),
(2,  2,  10, 1, DATE_SUB(NOW(), INTERVAL 6 MONTH),  1,    DATE_SUB(NOW(), INTERVAL 5 MONTH), 2, 3, 0, 'approved_option_1', 'Vacinação e chip',                   'Transporte próprio',            'Recolha ao final do dia',  DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(3,  3,  15, 2, DATE_SUB(NOW(), INTERVAL 4 MONTH),  NULL, NULL, 2, 1, 0, 'approved_option_1', 'Castração + desparasitação',         'Captura na véspera',            'Recolha 24h após cirurgia',DATE_SUB(NOW(), INTERVAL 4 MONTH), NOW()),
(4,  4,  10, 1, DATE_SUB(NOW(), INTERVAL 15 MONTH), NULL, NULL, 0, 2, 0, 'approved_option_1', 'OVH de duas gatas',                  'Entregar em jejum',             'Recolha ao fim da tarde',  DATE_SUB(NOW(), INTERVAL 15 MONTH), NOW()),
(5,  5,  12, 4, DATE_SUB(NOW(), INTERVAL 8 MONTH),  NULL, NULL, 1, 3, 0, 'approved_option_1', 'Esterilização em massa — Queluz',    NULL,                            NULL,                       DATE_SUB(NOW(), INTERVAL 8 MONTH), NOW()),
(6,  6,  17, 3, DATE_ADD(NOW(), INTERVAL 5 DAY),    3,    DATE_ADD(NOW(), INTERVAL 7 DAY), 1, 0, 0, 'approving', 'Cão a capturar — agendamento pendente','Captura com armadilha humana', NULL,                       DATE_SUB(NOW(), INTERVAL 2 WEEK), NOW()),
(7,  9,  10, 1, DATE_SUB(NOW(), INTERVAL 3 MONTH),  NULL, NULL, 0, 2, 0, 'approved_option_1', 'Controlo de saúde gatos Intendente',  NULL,                            NULL,                       DATE_SUB(NOW(), INTERVAL 3 MONTH), NOW()),
(8,  11, 10, 1, DATE_SUB(NOW(), INTERVAL 11 MONTH), 1,    DATE_SUB(NOW(), INTERVAL 10 MONTH), 1, 2, 0, 'approved_option_1', 'Vacinação anual',                    'Transportar em gaiola',         'Recolha após anestesia',   DATE_SUB(NOW(), INTERVAL 11 MONTH), NOW()),
(9,  12, 15, 5, DATE_SUB(NOW(), INTERVAL 3 WEEK),   NULL, NULL, 0, 1, 0, 'approved_option_1', 'Cadela grávida — parto assistido',   'Internamento previsto',         NULL,                       DATE_SUB(NOW(), INTERVAL 3 WEEK), NOW()),
(10, 13, 10, 1, DATE_SUB(NOW(), INTERVAL 4 MONTH),  NULL, NULL, 0, 3, 0, 'approved_option_1', 'Esterilização das gatas do Tojal',   NULL,                            NULL,                       DATE_SUB(NOW(), INTERVAL 4 MONTH), NOW());

-- ============================================================
-- TRATAMENTOS
-- ============================================================
-- treatment_types: 6=Castracao, 30=OVH, 41=Vacinacao, 9=Chip,
--   16=Desparasitacao Interna, 15=Desparasitacao Externa, 12=Consulta,
--   29=Medicacao, 25=Internamento
INSERT INTO treatments (id, appointment_id, treatment_type_id, vet_id, user_id, affected_animals, affected_animals_new, expense, status, date, notes, created_at, updated_at) VALUES
(1,  1,  6,  1, 10, 1, 0, 80.00,  'approved', DATE_SUB(NOW(), INTERVAL 10 MONTH), 'Castração — macho adulto',             NOW(), NOW()),
(2,  1,  30, 1, 10, 2, 0, 180.00, 'approved', DATE_SUB(NOW(), INTERVAL 10 MONTH), 'OVH — duas fêmeas adultas',            NOW(), NOW()),
(3,  1,  14, 1, 10, 3, 0, 0.00,   'approved', DATE_SUB(NOW(), INTERVAL 10 MONTH), 'Corte na orelha (identificação TNVR)', NOW(), NOW()),
(4,  2,  41, 1, 10, 5, 0, 75.00,  'approved', DATE_SUB(NOW(), INTERVAL 6 MONTH),  'Vacinação trivalente',                 NOW(), NOW()),
(5,  2,  9,  1, 10, 5, 0, 60.00,  'approved', DATE_SUB(NOW(), INTERVAL 6 MONTH),  'Colocação de chip — 5 animais',        NOW(), NOW()),
(6,  3,  6,  2, 15, 2, 0, 160.00, 'approved', DATE_SUB(NOW(), INTERVAL 4 MONTH),  'Castração de dois machos',             NOW(), NOW()),
(7,  3,  30, 2, 15, 1, 0, 95.00,  'approved', DATE_SUB(NOW(), INTERVAL 4 MONTH),  'OVH — fêmea jovem',                   NOW(), NOW()),
(8,  3,  17, 2, 15, 3, 0, 30.00,  'approved', DATE_SUB(NOW(), INTERVAL 4 MONTH),  'Desparasitação interna e externa',     NOW(), NOW()),
(9,  5,  30, 4, 12, 3, 0, 255.00, 'approved', DATE_SUB(NOW(), INTERVAL 8 MONTH),  'OVH — três fêmeas, Queluz',            NOW(), NOW()),
(10, 7,  12, 1, 10, 2, 0, 50.00,  'approved', DATE_SUB(NOW(), INTERVAL 3 MONTH),  'Consulta de rotina — Intendente',      NOW(), NOW()),
(11, 9,  25, 5, 15, 1, 0, 210.00, 'approved', DATE_SUB(NOW(), INTERVAL 3 WEEK),   'Internamento pós-parto — 3 dias',      NOW(), NOW()),
(12, 10, 30, 1, 10, 3, 0, 270.00, 'approved', DATE_SUB(NOW(), INTERVAL 4 MONTH),  'OVH — três gatas Calçada do Tojal',    NOW(), NOW());

-- ============================================================
-- ADOCOES
-- ============================================================
INSERT INTO adoptions (id, process_id, user_id, fat_id, name, name_after, age, gender, microchip, sterilized, vaccinated, processed, individual, docile, abandoned, adoption_date, adopter_id, status, features, history, created_at, updated_at) VALUES
(1, 10, 15, 2, 'Trovão', 'Max',     3, 'male',   '620098102345671', 1, 1, 1, 1, 1, 1, DATE_SUB(NOW(), INTERVAL 4 MONTH), 1, 'closed',  'Pelagem escura, porte médio, muito dócil',       'Resgatado após atropelamento na N250. Recuperação completa após cirurgia ortopédica.', NOW(), NOW()),
(2, 4,  10, 1, 'Fada',   'Lua',     4, 'female', '620098102345672', 1, 1, 1, 1, 1, 0, DATE_SUB(NOW(), INTERVAL 8 MONTH), 2, 'closed',  'Gata tricolor, olhos verdes',                    'Colónia de Belém. Muito tímida no início, agora completamente adaptada ao lar.', NOW(), NOW()),
(3, 1,  10, 1, 'Pirata', 'Pirata',  2, 'male',   '620098102345673', 1, 1, 1, 0, 1, 0, DATE_SUB(NOW(), INTERVAL 3 MONTH), 3, 'closed',  'Gato preto, orelha direita cortada (TNVR)',      'Da colónia da Mouraria. Habituado a pessoas.', NOW(), NOW()),
(4, 11, 10, 5, 'Mimosa', 'Mimosa',  5, 'female', '620098102345674', 1, 1, 1, 1, 1, 0, DATE_SUB(NOW(), INTERVAL 1 MONTH), 4, 'pending', 'Gata laranja, muito afectuosa',                  'Colónia dos Restauradores. Em processo de adoção com período de adaptação.', NOW(), NOW()),
(5, 5,  12, 3, 'Romeu',  'Simba',   1, 'male',   '620098102345675', 1, 1, 0, 1, 1, 0, DATE_SUB(NOW(), INTERVAL 5 MONTH), 5, 'closed',  'Gatinho jovem, pelo curto acastanhado',          'Nascido na colónia de Queluz. Socializado desde filhote.', NOW(), NOW()),
(6, 3,  15, 2, 'Lobo',   NULL,      4, 'male',   NULL,              0, 0, 0, 1, 0, 1, NOW(),                             NULL,'open',    'Cão de porte grande, pelo cinzento, assustado',  'Rua da Boavista, Porto. À procura de lar. Requer família experiente.', NOW(), NOW());

-- ============================================================
-- DOACOES
-- ============================================================
INSERT INTO donations (id, process_id, user_id, type, godfather_id, headquarter_id, protocol_id, value, date, notes, created_at, updated_at) VALUES
(1,  3,  10, 'private',     2,    NULL, NULL, 50.00,  DATE_SUB(NOW(), INTERVAL 5 MONTH),  'Donativo mensal de padrinhos — cães Boavista',     NOW(), NOW()),
(2,  3,  10, 'private',     2,    NULL, NULL, 50.00,  DATE_SUB(NOW(), INTERVAL 4 MONTH),  'Donativo mensal de padrinhos — cães Boavista',     NOW(), NOW()),
(3,  3,  10, 'private',     2,    NULL, NULL, 50.00,  DATE_SUB(NOW(), INTERVAL 3 MONTH),  'Donativo mensal de padrinhos — cães Boavista',     NOW(), NOW()),
(4,  12, 15, 'private',     3,    NULL, NULL, 120.00, DATE_SUB(NOW(), INTERVAL 2 WEEK),   'Apoio a cadela grávida — internamento',            NOW(), NOW()),
(5,  1,  10, 'private',     4,    NULL, NULL, 30.00,  DATE_SUB(NOW(), INTERVAL 1 MONTH),  'Ração oferecida (equivalente monetário)',           NOW(), NOW()),
(6,  NULL,1, 'headquarter', NULL, 2,    NULL, 500.00, DATE_SUB(NOW(), INTERVAL 6 MONTH),  'Donativo institucional BPI ao núcleo Lisboa',      NOW(), NOW()),
(7,  5,  12, 'private',     5,    NULL, NULL, 80.00,  DATE_SUB(NOW(), INTERVAL 7 MONTH),  'Apoio à esterilização em massa — Queluz',          NOW(), NOW()),
(8,  NULL,1, 'protocol',    NULL, NULL, 1,    1000.00,DATE_SUB(NOW(), INTERVAL 3 MONTH),  'Protocolo CML — apoio a colónias controladas',    NOW(), NOW());

-- ============================================================
-- PEDIDOS DE PROTOCOLO
-- ============================================================
INSERT INTO protocols_requests (id, council, name, email, phone, address, description, territory_id, process_id, protocol_id, user_id, created_at, updated_at) VALUES
(1, 'Lisboa',  'Luís Graça',       'luis.graca@cm-lisboa.pt',  '+351 213 001 001', 'Paços do Concelho, Lisboa',     'Pedido de colaboração para controlo de colónia em zona histórica da cidade.',    '1106', 1,  1, 1, DATE_SUB(NOW(), INTERVAL 12 MONTH), NOW()),
(2, 'Porto',   'Sandra Rocha',     'sandra.rocha@cm-porto.pt', '+351 222 001 002', 'Câmara Municipal, Porto',       'Colónia junto ao rio Douro. Câmara solicita intervenção de esterilização.',      '1312', 3,  2, 1, DATE_SUB(NOW(), INTERVAL 5 MONTH),  NOW()),
(3, 'Sintra',  'Rui Pacheco',      'rpacheco@cm-sintra.pt',    '+351 219 001 003', 'Edifício Câmara, Sintra',       'Proposta de protocolo formal para gestão de colónias no concelho.',             '1111', 5,  NULL,1,DATE_SUB(NOW(), INTERVAL 8 MONTH),  NOW()),
(4, 'Faro',    'Célia Bernardes',  'cbern@cm-faro.pt',         '+351 289 001 004', 'Câmara Municipal, Faro',        'Situação de excesso de gatos na zona histórica. Apoio urgente solicitado.',     '0805', 15, 3, 1, DATE_SUB(NOW(), INTERVAL 24 MONTH), NOW());

-- ============================================================
-- PRODUTOS DA LOJA
-- ============================================================
INSERT INTO store_products (id, name, vat, price, price_no_vat, expense, notes, created_at, updated_at) VALUES
(1, 'Camisola "Salva Vidas"',         23, 22.00, 17.89, 12.00, 'Tamanhos XS a XXL, algodão orgânico',              NOW(), NOW()),
(2, 'Totebag Animais de Rua',         23, 10.00, 8.13,  4.00,  'Algodão 100%, 38x42cm',                            NOW(), NOW()),
(3, 'Agenda Animais de Rua 2025',     6,  12.00, 11.32, 5.50,  'Capa em papel reciclado, 12 meses',                NOW(), NOW()),
(4, 'Pin metálico (pack 3)',           23, 6.50,  5.28,  2.00,  'Designs exclusivos com animais da associação',      NOW(), NOW()),
(5, 'Livro "Colónias Felinas"',       6,  18.00, 16.98, 8.00,  'Guia prático de gestão de colónias, 180 páginas',  NOW(), NOW()),
(6, 'Postal solidário (pack 10)',     23, 5.00,  4.07,  1.50,  'Artes originais de ilustradores portugueses',       NOW(), NOW());

-- ============================================================
-- ENCOMENDAS DA LOJA
-- ============================================================
INSERT INTO store_orders (id, reference, cart, recipient, address, user_id, shipment_date, expense, payment, notes, status, created_at, updated_at) VALUES
(1, 'AR-2025-0001', '{"1":{"qty":2},"2":{"qty":1}}',   'Sofia Andrade',    'Rua Almirante Reis, 120, 1150-023 Lisboa',   11, DATE_SUB(NOW(), INTERVAL 3 MONTH),  4.50,  'mbway',           NULL,                         'shipped',      DATE_SUB(NOW(), INTERVAL 3 MONTH),  NOW()),
(2, 'AR-2025-0002', '{"3":{"qty":1},"4":{"qty":2}}',   'Ricardo Leitão',   'Av. de Ceuta, 34, 1300-125 Lisboa',          11, DATE_SUB(NOW(), INTERVAL 2 MONTH),  4.50,  'bank_transfer',   'IBAN confirmado',            'shipped',      DATE_SUB(NOW(), INTERVAL 2 MONTH),  NOW()),
(3, 'AR-2025-0003', '{"1":{"qty":1},"5":{"qty":1}}',   'Débora Esteves',   'Rua das Flores, 8, 4050-262 Porto',          16, DATE_SUB(NOW(), INTERVAL 1 MONTH),  4.50,  'paypal',          NULL,                         'in_progress',  DATE_SUB(NOW(), INTERVAL 1 MONTH),  NOW()),
(4, 'AR-2025-0004', '{"2":{"qty":3},"6":{"qty":2}}',   'Vanessa Monteiro', 'Rua Alexandre Herculano, 55, 2710-040 Sintra',17,DATE_SUB(NOW(), INTERVAL 2 WEEK),  4.50,  'mbway',           NULL,                         'waiting',      DATE_SUB(NOW(), INTERVAL 2 WEEK),   NOW()),
(5, 'AR-2025-0005', '{"3":{"qty":2},"4":{"qty":1},"6":{"qty":3}}','Nuno Figueiredo','Rua Cândido dos Reis, 76, 8000-145 Faro',17, NULL,                4.50,  'credit_card',     'Cartão recusado — a rever',  'waiting',      DATE_SUB(NOW(), INTERVAL 1 WEEK),   NOW());

-- Produtos por encomenda
INSERT INTO store_orders_products (store_product_id, store_order_id, quantity, discount, discount_no_vat) VALUES
(1, 1, 2, 0.00, 0.00),
(2, 1, 1, 0.00, 0.00),
(3, 2, 1, 0.00, 0.00),
(4, 2, 2, 0.00, 0.00),
(1, 3, 1, 0.00, 0.00),
(5, 3, 1, 0.00, 0.00),
(2, 4, 3, 0.00, 0.00),
(6, 4, 2, 0.00, 0.00),
(3, 5, 2, 0.00, 0.00),
(4, 5, 1, 0.00, 0.00),
(6, 5, 3, 0.00, 0.00);

-- ============================================================
-- STOCK DA LOJA
-- ============================================================
INSERT INTO store_stock (id, user_id, store_product_id, quantity, notes, created_at, updated_at) VALUES
(1, 11, 1, 50,  'Stock inicial — camisolas',                     DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(2, 11, 2, 100, 'Stock inicial — totebags',                      DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(3, 11, 3, 80,  'Agendas 2025 recebidas em outubro 2024',        DATE_SUB(NOW(), INTERVAL 5 MONTH), NOW()),
(4, 11, 4, 200, 'Stock inicial — pins',                          DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(5, 11, 5, 40,  'Livros recebidos da gráfica',                   DATE_SUB(NOW(), INTERVAL 4 MONTH), NOW()),
(6, 11, 6, 150, 'Postais — primeira edição',                     DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(7, 11, 1, -3,  'Saída stock — encomendas 0001 e 0003',          DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW()),
(8, 11, 2, -4,  'Saída stock — encomendas 0001 e 0004',          DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW());

-- ============================================================
-- TRANSACOES DA LOJA
-- ============================================================
INSERT INTO store_transactions (id, description, user_id, amount, notes, created_at, updated_at) VALUES
(1, 'Encomenda AR-2025-0001 — pagamento recebido', 11, 58.50,   NULL,                   DATE_SUB(NOW(), INTERVAL 3 MONTH), NOW()),
(2, 'Encomenda AR-2025-0002 — pagamento recebido', 11, 31.00,   'Transferência IBAN',   DATE_SUB(NOW(), INTERVAL 2 MONTH), NOW()),
(3, 'Compra de embalagens e material de envio',    11, -45.00,  'Fornecedor: EmbalaJá', DATE_SUB(NOW(), INTERVAL 2 MONTH), NOW()),
(4, 'Encomenda AR-2025-0003 — pagamento recebido', 16, 44.50,   'PayPal confirmado',    DATE_SUB(NOW(), INTERVAL 1 MONTH), NOW());

-- ============================================================
-- FORNECEDORES
-- ============================================================
INSERT INTO suppliers (id, reference, store_order_id, store_product_id, notes, status, created_at, updated_at) VALUES
(1, 'FORN-2025-001', NULL, 1, 'Encomenda de 50 camisolas à Oficina Têxtil, Lda.',     'paid_out',       DATE_SUB(NOW(), INTERVAL 6 MONTH), NOW()),
(2, 'FORN-2025-002', NULL, 3, 'Agendas 2025 — Gráfica Impacto',                       'paid_out',       DATE_SUB(NOW(), INTERVAL 5 MONTH), NOW()),
(3, 'FORN-2025-003', NULL, 5, 'Livros "Colónias Felinas" — Editora Fauna',            'waiting_payment', DATE_SUB(NOW(), INTERVAL 2 MONTH), NOW());

-- ============================================================
-- VOUCHERS
-- ============================================================
INSERT INTO vouchers (id, reference, voucher, value, percent, client_name, client_email, expiration, status, created_at, updated_at) VALUES
(1, 'VOUCH-2025-001', 'AMIGO10',  NULL,  10, 'Sofia Andrade',    'sofia.andrade@gmail.com',     DATE_ADD(NOW(), INTERVAL 6 MONTH),  'unused', NOW(), NOW()),
(2, 'VOUCH-2025-002', 'AMIGO10',  NULL,  10, 'Ricardo Leitão',   'ricardo.leitao@hotmail.com',  DATE_ADD(NOW(), INTERVAL 6 MONTH),  'used',   NOW(), NOW()),
(3, 'VOUCH-2025-003', 'NATAL5',   5.00,  NULL,'Débora Esteves',   'debora.esteves@sapo.pt',      DATE_SUB(NOW(), INTERVAL 1 MONTH),  'unused', NOW(), NOW()),
(4, 'VOUCH-2025-004', 'FIEL15',   NULL,  15, 'Vanessa Monteiro', 'vanessa.monteiro@gmail.com',  DATE_ADD(NOW(), INTERVAL 3 MONTH),  'unused', NOW(), NOW()),
(5, 'VOUCH-2025-005', 'BEMVINDO', 3.00,  NULL,'Nuno Figueiredo',  'nuno.fig@gmail.com',          DATE_ADD(NOW(), INTERVAL 12 MONTH), 'unused', NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
