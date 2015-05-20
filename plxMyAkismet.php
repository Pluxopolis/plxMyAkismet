<?php
/**
 * Plugin plxMyAkismet
 *
 * @package	PLX
 * @author	Stephane F
 **/
class plxMyAkismet extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par défaut utilisée par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# droits pour accéder à la page config.php et admin.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);
		$this->setAdminProfil(PROFIL_ADMIN);

		# ajout du hook
		$this->addHook('plxMotorAddCommentaire', 'plxMotorAddCommentaire');
		$this->addHook('AdminTopBottom', 'AdminTopBottom');
		$this->addHook('AdminCommentsTop', 'AdminCommentsTop');

	}

	/**
	 * Méthode qui controle si le commentaire est un spam
	 *
	 * @return	stdio
	 * @author	Stéphane F.
	 **/
	public function plxMotorAddCommentaire() {

		$string  = 'include(PLX_PLUGINS."plxMyAkismet/Akismet.class.php");';
		$string .= '
			$akismet = new Akismet($this->racine, "'.$this->getParam('api_key').'");
			$akismet->setCommentAuthor($content["author"]);
			$akismet->setCommentAuthorEmail($content["mail"]);
			$akismet->setCommentAuthorURL($content["site"]);
			$akismet->setCommentContent($content["content"]);
			if($akismet->isCommentSpam()) {
				if(substr($content["filename"],0,1)=="_")
					$content["filename"]=str_replace("_","~",$content["filename"]);
				elseif(substr($content["filename"],0,1)!="~")
					$content["filename"]="~".$content["filename"];
			}
			return;
			';
		echo '<?php '.$string.' ?>';
	}

	public function AdminTopBottom() {
		if(basename($_SERVER['SCRIPT_NAME'])=='comments.php') {
			$string = "
			if((!empty(\$_GET['sel']) AND \$_GET['sel']=='spam') OR (isset(\$_SESSION['selCom']) AND \$_SESSION['selCom']=='spam')){
				\$comSel = 'spam';
				\$comSelMotif = '/^~[0-9]{4}.(.*).xml$/';
				\$_SESSION['selCom'] = 'spam';
				\$nbComPagination=\$plxAdmin->nbComments(\$comSelMotif);
				echo '<h2>".$this->getLang('SPAM_LIST')."</h2>';
			} else {
				\$comSel = ((isset(\$_SESSION['selCom']) AND !empty(\$_SESSION['selCom'])) ? \$_SESSION['selCom'] : 'all');
			}
			";
			echo '<?php '.$string.' ?>';
		}
	}

	public function AdminCommentsTop() {
		$string = "
			\$breadcrumbs[] = '<a '.(\$_SESSION['selCom']=='spam'?'class=\"selected\" ':'').'href=\"comments.php?sel=spam&amp;page=1\">Spam</a>&nbsp;('.\$plxAdmin->nbComments('/~[0-9]{4}.(.*).xml$/').')';
			function selectorSpam(\$id) {
				ob_start();
				plxUtils::printSelect('selection[]', array(''=> L_FOR_SELECTION, 'online' => L_COMMENT_SET_ONLINE, 'offline' => L_COMMENT_SET_OFFLINE,  '-'=>'-----','delete' => L_COMMENT_DELETE), '', false,'',\$id);
				return ob_get_clean();
			}
			if(\$comSel=='spam') {
				\$selector1=selectorSpam('id_selection1');
				\$selector2=selectorSpam('id_selection2');
			}
		";
		echo '<?php '.$string.' ?>';
	}
}
?>