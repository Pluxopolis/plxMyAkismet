<?php if(!defined('PLX_ROOT')) exit; ?>
<?php

# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {

	# sauvegarde des paramètres
	$plxPlugin->setParam('api_key', $_POST['api_key'], 'string');
	$plxPlugin->saveParams();

	# redirection sur la page de configuration du plugin
	header('Location: parametres_plugin.php?p=plxMyAkismet');
	exit;
}

# initialisation des paramètres par défaut
$params = $plxPlugin->getParams();
$api_key = empty($params['api_key']) ? '' : $params['api_key']['value'];

?>

<form class="inline-form" action="parametres_plugin.php?p=plxMyAkismet" method="post" id="form_plxMyAkismet">
	<fieldset>
		<p>
			<label for="api_key">Akismet API key :</label>
			<?php plxUtils::printInput('api_key', $api_key, 'text', '15-50'); ?>
		</p>
		<p class="in-action-bar">
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="save" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
		</p>
	</fieldset>
</form>

