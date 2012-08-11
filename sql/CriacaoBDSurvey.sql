CREATE TABLE questionarios(
	codQuestionario int(4) NOT NULL AUTO_INCREMENT,
	strNome varchar(100),
	strNomeInterno varchar(100),
	dtCriacao timestamp DEFAULT CURRENT_TIMESTAMP(),
	bAtivo tinyint(1),
	primary key(codQuestionario)
)ENGINE=INNODB;

CREATE TABLE questionario_grupos(
	codQuestGrupo int(4) NOT NULL AUTO_INCREMENT,
	codQuestionario int(4),
	strTitulo varchar(100),
	strSubTitulo varchar(100),
	intOrdem int(2),
	dtCriacao timestamp DEFAULT CURRENT_TIMESTAMP(),
	bAtivo tinyint(1),
	primary key(codQuestGrupo)
)ENGINE=INNODB;

CREATE TABLE questionario_campos(
	codQuestCampo int(4) NOT NULL AUTO_INCREMENT,
	codQuestGrupo int(4),
	strNome varchar(100),
	strNomeInterno varchar(100),
	strTipo	varchar(20),
	strDescricao varchar(70),
	strOpcoes text,
	strValores text,
	intOrdem int(2),
	bAtivo tinyint(1),
	dtCricao timestamp DEFAULT CURRENT_TIMESTAMP(),
	primary key(codQuestCampo)
)ENGINE=INNODB;

CREATE TABLE usuarios(
	codUsuario int(4) NOT NULL AUTO_INCREMENT,
	strNome varchar(90),
	strLogin varchar(30),
	strSenha varchar(70),
	chrAcesso char(1),
	bAtivo tinyint(1),
	dtCadastro timestamp DEFAULT CURRENT_TIMESTAMP(),
	primary key (codUsuario)
)ENGINE=INNODB;

INSERT INTO usuarios (strNome, strLogin, strSenha, chrAcesso, bAtivo)
	VALUES ('Elaine Cristina', 'elaine', '128bc66e311b0d7bbbfb297ccb14bd10', 'A', 1);