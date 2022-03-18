<?php
/**
 * Gestion des exceptions pour les exemples du tutoriel
 *
 * @author François Piat
 *
 */
class MonException extends Exception {
	/** @var boolean Indique si phase développement ou production */
	public static $is_dev = false;
	/** @var boolean Indique si arrêt quand erreur */
	protected $stop;
	/** @var array Texte des messages d'erreur */
	protected $textes = array(
						'Erreur inconnue',
						'Modèle invalide',
						'Nombre de cordes invalide',
						'Prix invalide');
	
	/**
	 * Constructeur
	 *
	 * @param string|int	$p1		Texte ou numéro d'erreur
	 * @param boolean		$p2		Indique si arrêt après erreur
	 */
	public function __construct($p1, $p2 = true) {
		if (is_numeric($p1)) {
			($p1 < 0 || $p1 >= count($this->textes)) && $p1 = 0;
			$msg = $this->textes[$p1];
		} else {
			$p1 = trim($p1);
			if (trim($p1) != '') {
				$msg = $p1;
			} else {
				$msg = $this->textes[0];
			}
		}
		parent::__construct($msg, 0);
		$this->stop = (bool) $p2;
	}
	/**
	 * Affichage du message d'erreur et arrêt éventuel du script
	 *
	 */
	public function __toString() {
		if (! self::$is_dev) {
			if ($this->stop) {
				ob_end_clean();
				exit('Site inaccessible');
			}
			return;
		}
		
		echo '<hr><b>Exception captur&eacute;e : </b>',
			$this->getMessage(),
			str_replace('#', '<br>#', $this->getTraceAsString());
		
		if ($this->stop) {
			exit('<hr>');
		}
	}
}
?>