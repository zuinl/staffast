CREATE TABLE IF NOT EXISTS  `tbl_gestor` (
            `ges_cpf` VARCHAR(11) NOT NULL UNIQUE,
            `ges_primeiro_nome` VARCHAR(20) NOT NULL,
            `ges_nome_completo` VARCHAR(80) NOT NULL,
            `ges_data_nascimento` DATE NOT NULL,
            `ges_sexo` VARCHAR(1) NOT NULL DEFAULT 'N',
            `ges_cargo` VARCHAR(40) NOT NULL,
            `ges_linkedin` VARCHAR(120) NULL,
            `ges_telefone` VARCHAR(15) NULL,
            `ges_telefone_profissional` VARCHAR(15) NULL,
            `ges_ramal` VARCHAR(6) NULL,
            `ges_cep` VARCHAR(9) NULL,
            `ges_endereco` VARCHAR(120) NULL,
            `ges_numero` VARCHAR(6) NULL,
            `ges_bairro` VARCHAR(120) NULL,
            `ges_cidade` VARCHAR(120) NULL,
            `ges_rg` VARCHAR(15) NULL,
            `ges_cnh` VARCHAR(20) NULL,
            `ges_cnh_tipo` VARCHAR(5) NULL,
            `ges_ctps` VARCHAR(20) NULL,
            `ges_nis` VARCHAR(20) NULL,
            `ges_tipo_sanguineo` VARCHAR(10) NULL,
            `ges_deficiencia` VARCHAR(250) NULL,
            `ges_plano_medico` VARCHAR(30) NULL,
            `ges_hipertenso` INT(1) NULL,
            `ges_diabetico` INT(1) NULL,
            `ges_cartao_sus` VARCHAR(20) NULL,
            `ges_alergias` VARCHAR(200) NULL,
            `ges_medicamentos` VARCHAR(200) NULL,
            `ges_filhos` VARCHAR(2) NULL,
            `ges_estado_civil` VARCHAR(30) NULL,
            `ges_formacao` VARCHAR(120) NULL,
            `ges_apresentacao` VARCHAR(250) NULL,
            `ges_tipo` VARCHAR(1) NOT NULL,
            `ges_id_interno` VARCHAR(25),
            `ges_foto` VARCHAR(250),
            `ges_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `ges_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            `ges_ativo` INT NOT NULL DEFAULT 1,
            `usu_id` INT NOT NULL,
            PRIMARY KEY (`ges_cpf`));
            
            
            CREATE TABLE IF NOT EXISTS  `tbl_colaborador` (
            `col_cpf` VARCHAR(11) NOT NULL UNIQUE,
            `col_primeiro_nome` VARCHAR(20) NOT NULL,
            `col_nome_completo` VARCHAR(80) NOT NULL,
            `col_data_nascimento` DATE NOT NULL,
            `col_sexo` VARCHAR(1) NOT NULL DEFAULT 'N',
            `col_cargo` VARCHAR(40) NOT NULL,
            `col_telefone` VARCHAR(15) NULL,
            `col_telefone_profissional` VARCHAR(15) NULL,
            `col_cep` VARCHAR(9) NULL,
            `col_endereco` VARCHAR(120) NULL,
            `col_numero` VARCHAR(6) NULL,
            `col_bairro` VARCHAR(120) NULL,
            `col_cidade` VARCHAR(120) NULL,
            `col_rg` VARCHAR(15) NULL,
            `col_cnh` VARCHAR(20) NULL,
            `col_cnh_tipo` VARCHAR(5) NULL,
            `col_ctps` VARCHAR(20) NULL,
            `col_nis` VARCHAR(20) NULL,
            `col_tipo_sanguineo` VARCHAR(10) NULL,
            `col_deficiencia` VARCHAR(250) NULL,
            `col_plano_medico` VARCHAR(30) NULL,
            `col_hipertenso` INT(1) NULL,
            `col_diabetico` INT(1) NULL,
            `col_cartao_sus` VARCHAR(20) NULL,
            `col_alergias` VARCHAR(200) NULL,
            `col_medicamentos` VARCHAR(200) NULL,
            `col_filhos` VARCHAR(2) NULL,
            `col_estado_civil` VARCHAR(30) NULL,
            `col_formacao` VARCHAR(120) NULL,
            `col_apresentacao` VARCHAR(250) NULL,
            `col_id_interno` VARCHAR(25),
            `col_foto` VARCHAR(250),
            `col_ativo` INT NOT NULL DEFAULT 1,
            `col_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `col_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            `usu_id` INT NOT NULL,
            `ges_importado` INT NOT NULL DEFAULT 0,
            PRIMARY KEY (`col_cpf`));



CREATE TABLE IF NOT EXISTS `tbl_mensagem_funcionario` (
            `men_id` INT NOT NULL,
            `cpf` VARCHAR(11) NOT NULL);
            
            
            CREATE TABLE IF NOT EXISTS `tbl_mensagem` (
            `men_id` INT NOT NULL AUTO_INCREMENT,
            `men_titulo` VARCHAR(100) NOT NULL,
            `men_texto` LONGTEXT NOT NULL,
            `men_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `men_data_expiracao` DATETIME NOT NULL,
            `cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`men_id`));
            
            CREATE TABLE IF NOT EXISTS  `tbl_resposta_candidato` (
            `can_id` INT NOT NULL,
            `res_id` INT NOT NULL);
            
            CREATE TABLE IF NOT EXISTS  `tbl_pergunta_resposta` (
            `res_id` INT NOT NULL AUTO_INCREMENT,
            `res_opc_um` INT NOT NULL,
            `res_opc_dois` INT NOT NULL,
            `res_opc_tres` INT NOT NULL,
            `res_opc_quatro` INT NOT NULL,
            `per_id` INT NOT NULL,
            PRIMARY KEY (`res_id`));
              
              CREATE TABLE IF NOT EXISTS  `tbl_pergunta_processo` (
            `per_id` INT NOT NULL AUTO_INCREMENT,
            `per_titulo` VARCHAR(120) NOT NULL,
            `per_descricao` VARCHAR(500) NULL,
            `per_opc_um` VARCHAR(80) NOT NULL,
            `per_opc_um_competencia` VARCHAR(30) NOT NULL,
            `per_opc_dois` VARCHAR(80) NOT NULL,
            `per_opc_dois_competencia` VARCHAR(30) NOT NULL,
            `per_opc_tres` VARCHAR(80) NOT NULL,
            `per_opc_tres_competencia` VARCHAR(30) NOT NULL,
            `per_opc_quatro` VARCHAR(80) NOT NULL,
            `per_opc_quatro_competencia` VARCHAR(30) NOT NULL,
            `sel_id` INT NOT NULL,
            PRIMARY KEY (`per_id`));
              
              CREATE TABLE IF NOT EXISTS  `tbl_processo_seletivo` (
            `sel_id` INT NOT NULL AUTO_INCREMENT,
            `sel_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `sel_data_encerramento` DATETIME NOT NULL,
            `sel_titulo` VARCHAR(80) NOT NULL,
            `sel_vagas` INT NOT NULL,
            `sel_descricao` VARCHAR(800) NOT NULL,
            `ges_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`sel_id`));
              
              CREATE TABLE IF NOT EXISTS  `tbl_avaliacao_gestao` (
            `avg_id` INT NOT NULL AUTO_INCREMENT,
            `avg_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `avg_sessao_um` INT NOT NULL,
            `avg_sessao_um_obs` VARCHAR(400) NULL,
            `avg_sessao_dois` INT NOT NULL,
            `avg_sessao_dois_obs` VARCHAR(400) NULL,
            `avg_sessao_tres` INT NOT NULL,
            `avg_sessao_tres_obs` VARCHAR(400) NULL,
            `avg_sessao_quatro` INT NOT NULL,
            `avg_sessao_quatro_obs` VARCHAR(400) NULL,
            `avg_sessao_cinco` INT NOT NULL,
            `avg_sessao_cinco_obs` VARCHAR(400) NULL,
            `avg_sessao_seis` INT NOT NULL,
            `avg_sessao_seis_obs` VARCHAR(400) NULL,
            `avg_sessao_sete` INT NOT NULL,
            `avg_sessao_sete_obs` VARCHAR(400) NULL,
            `avg_sessao_oito` INT NOT NULL,
            `avg_sessao_oito_obs` VARCHAR(400) NULL,
            `avg_sessao_nove` INT NOT NULL,
            `avg_sessao_nove_obs` VARCHAR(400) NULL,
            `avg_sessao_dez` INT NOT NULL,
            `avg_sessao_dez_obs` VARCHAR(400) NULL,
            `ges_cpf` VARCHAR(11) NULL,
            `set_id` INT(11) NULL,
            `usu_id` INT(11) NOT NULL,
            `cod_string` VARCHAR(6) NULL,
            PRIMARY KEY (`avg_id`));
            
            CREATE TABLE IF NOT EXISTS  `tbl_autoavaliacao` (
            `ata_id` INT NOT NULL AUTO_INCREMENT,
            `ata_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `ata_data_preenchida` DATETIME NOT NULL DEFAULT NOW(),
            `ata_preenchida` INT NOT NULL DEFAULT 0,
            `ata_sessao_um` INT NOT NULL,
            `ata_sessao_um_obs` VARCHAR(400) NULL,
            `ata_sessao_dois` INT NOT NULL,
            `ata_sessao_dois_obs` VARCHAR(400) NULL,
            `ata_sessao_tres` INT NOT NULL,
            `ata_sessao_tres_obs` VARCHAR(400) NULL,
            `ata_sessao_quatro` INT NOT NULL,
            `ata_sessao_quatro_obs` VARCHAR(400) NULL,
            `ata_sessao_cinco` INT NOT NULL,
            `ata_sessao_cinco_obs` VARCHAR(400) NULL,
            `ata_sessao_seis` INT NOT NULL,
            `ata_sessao_seis_obs` VARCHAR(400) NULL,
            `ata_sessao_sete` INT NOT NULL,
            `ata_sessao_sete_obs` VARCHAR(400) NULL,
            `ata_sessao_oito` INT NOT NULL,
            `ata_sessao_oito_obs` VARCHAR(400) NULL,
            `ata_sessao_nove` INT NOT NULL,
            `ata_sessao_nove_obs` VARCHAR(400) NULL,
            `ata_sessao_dez` INT NOT NULL,
            `ata_sessao_dez_obs` VARCHAR(400) NULL,
            `ata_sessao_onze` INT NOT NULL,
            `ata_sessao_onze_obs` VARCHAR(400) NULL,
            `ata_sessao_doze` INT NOT NULL,
            `ata_sessao_doze_obs` VARCHAR(400) NULL,
            `ata_sessao_treze` INT NOT NULL,
            `ata_sessao_treze_obs` VARCHAR(400) NULL,
            `ata_sessao_quatorze` INT NOT NULL,
            `ata_sessao_quatorze_obs` VARCHAR(400) NULL,
            `ata_sessao_quinze` INT NOT NULL,
            `ata_sessao_quinze_obs` VARCHAR(400) NULL,
            `ata_sessao_dezesseis` INT NOT NULL,
            `ata_sessao_dezesseis_obs` VARCHAR(400) NULL,
            `ata_sessao_dezessete` INT NOT NULL,
            `ata_sessao_dezessete_obs` VARCHAR(400) NULL,
            `ata_sessao_dezoito` INT NOT NULL,
            `ata_sessao_dezoito_obs` VARCHAR(400) NULL,
            `ata_sessao_dezenove` INT NOT NULL,
            `ata_sessao_dezenove_obs` VARCHAR(400) NULL,
            `ata_sessao_vinte` INT NOT NULL,
            `ata_sessao_vinte_obs` VARCHAR(400) NULL,
            `col_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`ata_id`),
              FOREIGN KEY (`col_cpf`)
              REFERENCES `TBL_COLABORADOR` (`col_cpf`));
              
              
              CREATE TABLE IF NOT EXISTS  `tbl_avaliacao` (
            `ava_id` INT NOT NULL AUTO_INCREMENT,
            `ava_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
            `ava_data_liberacao` DATETIME NOT NULL,
            `ava_visualizada` INT NOT NULL DEFAULT 0,
            `ava_modelo_id` INT NOT NULL DEFAULT 0,
            `ava_sessao_um` INT NOT NULL,
            `ava_sessao_um_obs` VARCHAR(400) NULL,
            `ava_sessao_dois` INT NOT NULL,
            `ava_sessao_dois_obs` VARCHAR(400) NULL,
            `ava_sessao_tres` INT NOT NULL,
            `ava_sessao_tres_obs` VARCHAR(400) NULL,
            `ava_sessao_quatro` INT NOT NULL,
            `ava_sessao_quatro_obs` VARCHAR(400) NULL,
            `ava_sessao_cinco` INT NOT NULL,
            `ava_sessao_cinco_obs` VARCHAR(400) NULL,
            `ava_sessao_seis` INT NOT NULL,
            `ava_sessao_seis_obs` VARCHAR(400) NULL,
            `ava_sessao_sete` INT NOT NULL,
            `ava_sessao_sete_obs` VARCHAR(400) NULL,
            `ava_sessao_oito` INT NOT NULL,
            `ava_sessao_oito_obs` VARCHAR(400) NULL,
            `ava_sessao_nove` INT NOT NULL,
            `ava_sessao_nove_obs` VARCHAR(400) NULL,
            `ava_sessao_dez` INT NOT NULL,
            `ava_sessao_dez_obs` VARCHAR(400) NULL,
            `ava_sessao_onze` INT NOT NULL,
            `ava_sessao_onze_obs` VARCHAR(400) NULL,
            `ava_sessao_doze` INT NOT NULL,
            `ava_sessao_doze_obs` VARCHAR(400) NULL,
            `ava_sessao_treze` INT NOT NULL,
            `ava_sessao_treze_obs` VARCHAR(400) NULL,
            `ava_sessao_quatorze` INT NOT NULL,
            `ava_sessao_quatorze_obs` VARCHAR(400) NULL,
            `ava_sessao_quinze` INT NOT NULL,
            `ava_sessao_quinze_obs` VARCHAR(400) NULL,
            `ava_sessao_dezesseis` INT NOT NULL,
            `ava_sessao_dezesseis_obs` VARCHAR(400) NULL,
            `ava_sessao_dezessete` INT NOT NULL,
            `ava_sessao_dezessete_obs` VARCHAR(400) NULL,
            `ava_sessao_dezoito` INT NOT NULL,
            `ava_sessao_dezoito_obs` VARCHAR(400) NULL,
            `ava_sessao_dezenove` INT NOT NULL,
            `ava_sessao_dezenove_obs` VARCHAR(400) NULL,
            `ava_sessao_vinte` INT NOT NULL,
            `ava_sessao_vinte_obs` VARCHAR(400) NULL,
            `ges_cpf` VARCHAR(11) NOT NULL,
            `col_cpf` VARCHAR(11) NOT NULL,
            PRIMARY KEY (`ava_id`),
              FOREIGN KEY (`col_cpf`) REFERENCES `TBL_COLABORADOR` (`col_cpf`));
              
              CREATE TABLE IF NOT EXISTS  `tbl_candidato` (
            `can_id` INT NOT NULL AUTO_INCREMENT,
            `can_nome` VARCHAR(80) NOT NULL,
            `can_linkedin` VARCHAR(120) NULL,
            `can_email` VARCHAR(120) NOT NULL,
            `can_telefone` VARCHAR(15) NOT NULL,
            `can_apresentacao` VARCHAR(1000) NOT NULL,
            `can_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `can_curriculo` VARCHAR(200),
            `sel_id` INT NOT NULL,
            PRIMARY KEY (`can_id`));
            
            
            CREATE TABLE IF NOT EXISTS  tbl_setor_funcionario (
            set_id INT NOT NULL, 
            col_cpf VARCHAR(11) NOT NULL,
            ges_cpf VARCHAR(11) NOT NULL,
            data_add DATETIME NOT NULL DEFAULT NOW());
            
            
            CREATE TABLE IF NOT EXISTS  `tbl_setor` (
            `set_id` INT NOT NULL AUTO_INCREMENT,
            `set_nome` VARCHAR(50) NOT NULL,
            `set_local` VARCHAR(80) NULL,
            `set_ativo` INT NOT NULL DEFAULT 1,
            `set_descricao` VARCHAR(150) NULL,
            `set_data_cadastro` DATETIME NOT NULL DEFAULT NOW(),
            `set_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
            PRIMARY KEY (`set_id`));


            CREATE TABLE IF NOT EXISTS `tbl_documento` (
  `doc_id` INT NOT NULL AUTO_INCREMENT,
  `doc_titulo` VARCHAR(70) NOT NULL,
  `doc_tipo` VARCHAR(45) NOT NULL,
  `doc_caminho` VARCHAR(180) NOT NULL,
  `doc_data_upload` DATETIME NOT NULL DEFAULT NOW(),
  `ges_cpf` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`doc_id`));

  CREATE TABLE IF NOT EXISTS `tbl_documento_dono` (
  `doc_id` INT NOT NULL,
  `cpf` VARCHAR(11) NOT NULL);

    CREATE TABLE IF NOT EXISTS  tbl_ocorrencia (
  
    ocr_id INT NOT NULL AUTO_INCREMENT,
    ocr_data DATETIME NOT NULL DEFAULT NOW(),
    ocr_titulo VARCHAR(60) NOT NULL,
    ocr_tipo VARCHAR(40) NOT NULL,
    ocr_gravidade INT NOT NULL DEFAULT 1,
    ocr_descricao LONGTEXT,
    ocr_medidas LONGTEXT,
    ocr_visivel INT NOT NULL DEFAULT 1,
    set_id INT NOT NULL DEFAULT 0,
    cpf VARCHAR (11) NOT NULL,
    PRIMARY KEY (ocr_id)
);

CREATE TABLE IF NOT EXISTS  tbl_okr (

    okr_id INT NOT NULL AUTO_INCREMENT,
    okr_titulo VARCHAR(60) NOT NULL,
    okr_descricao LONGTEXT,
    okr_tipo VARCHAR (30) NOT NULL,
    okr_visivel INT NOT NULL DEFAULT 1,
    okr_goal_money DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    okr_goal_number INT NOT NULL DEFAULT 0,
    okr_prazo DATETIME NOT NULL,
    okr_concluida INT NOT NULL DEFAULT 0,
    okr_arquivada INT NOT NULL DEFAULT 0,
    okr_data_criacao DATETIME NOT NULL DEFAULT NOW(),
    ges_cpf VARCHAR(11) NOT NULL,
    PRIMARY KEY (okr_id));

CREATE TABLE IF NOT EXISTS  tbl_key_result (

  krs_id INT NOT NULL AUTO_INCREMENT,
  krs_tipo VARCHAR(30) NOT NULL,
  krs_data_criacao DATETIME NOT NULL DEFAULT NOW(),
  krs_titulo VARCHAR (120) NOT NULL,
  krs_goal DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  krs_current DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  krs_ultima_atualizacao DATETIME NOT NULL DEFAULT NOW(),
  okr_id INT NOT NULL,
  PRIMARY KEY (krs_id));

    
CREATE TABLE IF NOT EXISTS  tbl_okr_colaborador (
	okr_id INT NOT NULL,
    col_cpf VARCHAR(11) NOT NULL
);

CREATE TABLE IF NOT EXISTS  tbl_okr_gestor (
	okr_id INT NOT NULL,
    ges_cpf VARCHAR(11) NOT NULL 
);

CREATE TABLE IF NOT EXISTS  tbl_okr_setor (
	okr_id INT NOT NULL,
    set_id INT NOT NULL
);

CREATE TABLE IF NOT EXISTS `tbl_setor_competencia` 
( `set_id` INT NOT NULL UNIQUE, 
 `um` VARCHAR(30) NOT NULL , 
 `dois` VARCHAR(30) NOT NULL , 
 `tres` VARCHAR(30) NOT NULL , 
 `quatro` VARCHAR(30) NOT NULL , 
 `cinco` VARCHAR(30) NULL , 
 `seis` VARCHAR(30) NULL,
 `avs_liberada` DATETIME NOT NULL DEFAULT NOW());


 CREATE TABLE IF NOT EXISTS `tbl_avaliacao_setor` ( 
   `avs_id` INT NOT NULL AUTO_INCREMENT , 
   `avs_data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
   `um` INT NOT NULL , 
   `um_obs` VARCHAR(400), 
   `dois` INT NOT NULL , 
   `dois_obs` VARCHAR(400), 
   `tres` INT NOT NULL , 
   `tres_obs` VARCHAR(400), 
   `quatro` INT NOT NULL , 
   `quatro_obs` VARCHAR(400), 
   `cinco` INT , 
   `cinco_obs` VARCHAR(400), 
   `seis` INT, 
   `seis_obs` VARCHAR(400), 
   `set_id` INT, 
   `cpf` VARCHAR(11) NOT NULL , 
   PRIMARY KEY (`avs_id`));

   CREATE TABLE IF NOT EXISTS `tbl_evento` ( 
    `eve_id` INT NOT NULL AUTO_INCREMENT , 
    `eve_titulo` VARCHAR(70) NOT NULL , 
    `eve_descricao` TEXT NOT NULL , 
    `eve_data_inicial` DATE NOT NULL , 
    `eve_hora_inicial` TIME NOT NULL , 
    `eve_data_final` DATE NOT NULL , 
    `eve_hora_final` TIME NOT NULL , 
    `eve_local` VARCHAR(150) NOT NULL , 
    `eve_na_empresa` INT NOT NULL DEFAULT '1' ,
    `eve_status` INT NOT NULL DEFAULT '1' ,
    `eve_data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
    `eve_data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  
    `ges_cpf` VARCHAR(11) NOT NULL , PRIMARY KEY (`eve_id`));

    CREATE TABLE IF NOT EXISTS `tbl_evento_participante` ( 
      `eve_id` INT NOT NULL , 
      `cpf` VARCHAR(11) NOT NULL , 
      `gestor` INT NOT NULL DEFAULT '0' ,
      `colaborador` INT NOT NULL DEFAULT '0',
      `confirmado` INT NOT NULL DEFAULT '0');

      CREATE TABLE IF NOT EXISTS `tbl_reuniao` ( 
    `reu_id` INT NOT NULL AUTO_INCREMENT , 
    `reu_pauta` VARCHAR(100) NOT NULL , 
    `reu_descricao` TEXT NOT NULL , 
    `reu_data` DATE NOT NULL , 
    `reu_hora` TIME NOT NULL ,  
    `reu_local` VARCHAR(150) NOT NULL, 
    `reu_objetivo` VARCHAR(300) NOT NULL,
    `reu_ata` TEXT NULL DEFAULT NULL,
    `reu_concluida` INT NOT NULL DEFAULT '0' ,
    `reu_objetivo_atingido` INT NOT NULL DEFAULT '0' ,
    `reu_data_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    `reu_data_atualizacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,   
    `ges_cpf` VARCHAR(11) NOT NULL , PRIMARY KEY (`reu_id`));

    CREATE TABLE IF NOT EXISTS `tbl_reuniao_integrante` (  
      `reu_id` INT NOT NULL , 
      `cpf` VARCHAR(11) NOT NULL , 
      `gestor` INT NOT NULL DEFAULT '0' ,
      `colaborador` INT NOT NULL DEFAULT '0',
      `confirmado` INT NOT NULL DEFAULT '0');

    CREATE TABLE IF NOT EXISTS `tbl_feedback` ( 
      `fee_id` INT NOT NULL AUTO_INCREMENT , 
      `fee_texto` VARCHAR(200) NOT NULL ,
      `fee_comecar` VARCHAR(200) NULL , 
      `fee_continuar` VARCHAR(200) NULL ,
      `fee_parar` VARCHAR(200) NULL ,
      `ges_cpf` VARCHAR(11) NULL DEFAULT NULL, 
      `col_cpf` VARCHAR(11) NULL DEFAULT NULL, 
      `fee_cpf` VARCHAR(11) NOT NULL , 
      `fee_visualizado` INT NOT NULL DEFAULT 0 , 
      `fee_criacao` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
      PRIMARY KEY (`fee_id`));

      CREATE TABLE IF NOT EXISTS `tbl_gestor_funcionario` ( 
        `ges_cpf` VARCHAR(11) NOT NULL , 
        `col_cpf` VARCHAR(11) NOT NULL , 
        `set_id` INT NOT NULL );

        CREATE TABLE IF NOT EXISTS `tbl_reuniao_evento` ( 
          `reu_id` INT NOT NULL , 
          `eve_id` INT NOT NULL );

          CREATE TABLE IF NOT EXISTS `tbl_reuniao_okr` ( 
          `reu_id` INT NOT NULL , 
          `okr_id` INT NOT NULL );



          CREATE TABLE IF NOT EXISTS `tbl_pdi` (
  
  `pdi_id` INT NOT NULL AUTO_INCREMENT,
  
  `pdi_titulo` VARCHAR(150) NOT NULL,
  
  `pdi_prazo` DATETIME NOT NULL,
  
  `pdi_cpf` VARCHAR(11) NOT NULL,
  
  `pdi_status` INT NOT NULL DEFAULT 3,

  `pdi_arquivado` INT NOT NULL DEFAULT 0,
  
  `pdi_publico` INT NOT NULL DEFAULT 0,
  
  `pdi_data_criacao` DATETIME NOT NULL DEFAULT NOW(),
  
  `pdi_data_alteracao` DATETIME NOT NULL DEFAULT NOW(),
  
  `ges_cpf` VARCHAR(11) NULL DEFAULT NULL,
  PRIMARY KEY (`pdi_id`));

CREATE TABLE IF NOT EXISTS `tbl_pdi_competencia` (
  
  `id` INT NOT NULL AUTO_INCREMENT,
  
  `pdi_id` INT NOT NULL,
  
  `descricao` VARCHAR(50) NOT NULL,
  
  `status` INT NOT NULL DEFAULT 3,
  
  PRIMARY KEY (`id`));


CREATE TABLE IF NOT EXISTS `tbl_pdi_competencia_meta` (
  
  `id` INT NOT NULL AUTO_INCREMENT,
  
  `cpt_id` INT NOT NULL,
  
  `descricao` VARCHAR(300) NOT NULL,
  
  `status` INT NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`));


  CREATE TABLE IF NOT EXISTS `tbl_pdi_anotacao` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `pdi_id` INT(11) NOT NULL , 
    `anotacao` TEXT NULL , 
    `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    PRIMARY KEY (`id`));

    CREATE TABLE IF NOT EXISTS `tbl_ponto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `cpf` VARCHAR(11) NOT NULL,
  `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` INT NOT NULL,
  `latitude` VARCHAR(20) NULL DEFAULT NULL,
  `longitude` VARCHAR(20) NULL DEFAULT NULL,
  `endereco` TEXT NULL DEFAULT NULL,
  `editado` INT NOT NULL DEFAULT 0,
  `cpf_edicao` VARCHAR(11) NULL DEFAULT NULL,
  `data_edicao` DATETIME NULL DEFAULT NULL,
  `motivo_edicao` TEXT NULL DEFAULT NULL,
  `anotacoes` TEXT NULL DEFAULT NULL,
  `app` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`));

  CREATE TABLE IF NOT EXISTS `tbl_funcionario_horario` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `cpf` VARCHAR(11) NOT NULL , 
    `entrada_monday` VARCHAR(10) NOT NULL , 
    `pausa_monday` VARCHAR(10) NOT NULL ,
    `retorno_monday` VARCHAR(10) NOT NULL , 
    `saida_monday` VARCHAR(10) NOT NULL ,
    `entrada_tuesday` VARCHAR(10) NOT NULL , 
    `pausa_tuesday` VARCHAR(10) NOT NULL ,
    `retorno_tuesday` VARCHAR(10) NOT NULL , 
    `saida_tuesday` VARCHAR(10) NOT NULL ,
    `entrada_wednesday` VARCHAR(10) NOT NULL , 
    `pausa_wednesday` VARCHAR(10) NOT NULL ,
    `retorno_wednesday` VARCHAR(10) NOT NULL , 
    `saida_wednesday` VARCHAR(10) NOT NULL ,
    `entrada_thursday` VARCHAR(10) NOT NULL , 
    `pausa_thursday` VARCHAR(10) NOT NULL ,
    `retorno_thursday` VARCHAR(10) NOT NULL , 
    `saida_thursday` VARCHAR(10) NOT NULL ,
    `entrada_friday` VARCHAR(10) NOT NULL , 
    `pausa_friday` VARCHAR(10) NOT NULL ,
    `retorno_friday` VARCHAR(10) NOT NULL , 
    `saida_friday` VARCHAR(10) NOT NULL ,
    `entrada_saturday` VARCHAR(10) NOT NULL , 
    `pausa_saturday` VARCHAR(10) NOT NULL ,
    `retorno_saturday` VARCHAR(10) NOT NULL , 
    `saida_saturday` VARCHAR(10) NOT NULL ,
    `entrada_sunday` VARCHAR(10) NOT NULL , 
    `pausa_sunday` VARCHAR(10) NOT NULL ,
    `retorno_sunday` VARCHAR(10) NOT NULL , 
    `saida_sunday` VARCHAR(10) NOT NULL ,
    `dt_inicial` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
    `dt_final` TIMESTAMP NULL DEFAULT NULL ,
    `noturno` INT NOT NULL DEFAULT 0 ,
    `pausa_flexivel` INT NOT NULL DEFAULT 1 ,
    `horario_flexivel` INT NOT NULL DEFAULT 0 ,
    `hora_extra` INT NOT NULL DEFAULT 1 ,
    `tolerancia` INT NOT NULL DEFAULT 0 ,
    `ponto_site` INT NOT NULL DEFAULT 0 ,
    PRIMARY KEY (`id`));


    CREATE TABLE IF NOT EXISTS `tbl_modelo_avaliacao` ( 
    `id` INT NOT NULL AUTO_INCREMENT, 
    `data_criacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `cpf_criador` VARCHAR(11) NOT NULL , 
    `titulo` VARCHAR(60) NOT NULL,
    `um` VARCHAR(35) NOT NULL,
	  `dois` VARCHAR(35) NOT NULL,
    `tres` VARCHAR(35) NOT NULL,
    `quatro` VARCHAR(35) NOT NULL,
    `cinco` VARCHAR(35) NULL DEFAULT NULL,
    `seis` VARCHAR(35) NULL DEFAULT NULL,
    `sete` VARCHAR(35) NULL DEFAULT NULL,
    `oito` VARCHAR(35) NULL DEFAULT NULL,
    `nove` VARCHAR(35) NULL DEFAULT NULL,
    `dez` VARCHAR(35) NULL DEFAULT NULL,
    `onze` VARCHAR(35) NULL DEFAULT NULL,
    `doze` VARCHAR(35) NULL DEFAULT NULL,
    `treze` VARCHAR(35) NULL DEFAULT NULL,
    `quatorze` VARCHAR(35) NULL DEFAULT NULL,
    `quinze` VARCHAR(35) NULL DEFAULT NULL,
    `dezesseis` VARCHAR(35) NULL DEFAULT NULL,
    `dezessete` VARCHAR(35) NULL DEFAULT NULL,
    `dezoito` VARCHAR(35) NULL DEFAULT NULL,
    `dezenove` VARCHAR(35) NULL DEFAULT NULL,
    `vinte` VARCHAR(35) NULL DEFAULT NULL,
    `ativo` INT NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`));

      CREATE TABLE IF NOT EXISTS `tbl_colaborador_modelo_avaliacao` ( 
        `col_cpf` VARCHAR(11) NOT NULL , 
        `modelo_id` INT NOT NULL, 
        `data_add` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP );

        CREATE TABLE IF NOT EXISTS `tbl_krs_anotacao` ( 
          `id` INT NOT NULL AUTO_INCREMENT , 
          `krs_id` INT NOT NULL , 
          `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
          `anotacao` TEXT NOT NULL , 
          `col_cpf` VARCHAR(11) NULL DEFAULT NULL , 
          `ges_cpf` VARCHAR(11) NULL DEFAULT NULL , PRIMARY KEY (`id`));

    CREATE TABLE IF NOT EXISTS `tbl_feedback_pedido` ( 
      `id` INT NOT NULL AUTO_INCREMENT , 
      `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
      `motivo` TEXT NULL DEFAULT NULL , 
      `cpf_solicitante` VARCHAR(11) NOT NULL , 
      `cpf_destinatario` VARCHAR(11) NOT NULL , 
      `fee_id` INT NULL DEFAULT NULL , PRIMARY KEY (`id`));


      CREATE TABLE IF NOT EXISTS `tbl_reuniao_anotacao` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `reu_id` INT NOT NULL , 
        `cpf` VARCHAR(11) NOT NULL , 
        `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        `anotacao` TEXT NOT NULL , PRIMARY KEY (`id`))
        ;

        CREATE TABLE IF NOT EXISTS `tbl_evento_anotacao` ( 
        `id` INT NOT NULL AUTO_INCREMENT , 
        `eve_id` INT NOT NULL , 
        `cpf` VARCHAR(11) NOT NULL , 
        `data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        `anotacao` TEXT NOT NULL , PRIMARY KEY (`id`))
        ;


    