<?php
/*
Order SMS Notification ( Admin functions  )
©2020 Daniel Esparza, inspirado por #openliveit #dannydshore | Consultoría en servicios y soluciones de entorno web - https://danielesparza.studio/
*/


if( function_exists( 'admin_menu_desparza' ) ) { 
    //( do nothing... )
} else {
	add_action( 'admin_menu', 'admin_menu_desparza' );
	function admin_menu_desparza(){
		add_menu_page( 'DE Plugins', 'DE Plugins', 'manage_options', 'desparza-menu', 'dewp_desparza_function', 'dashicons-editor-code', 90 );
		add_submenu_page( 'desparza-menu', 'Sobre Daniel Esparza', 'Sobre Daniel Esparza', 'manage_options', 'desparza-menu' );
	
    function dewp_desparza_function(){
		ob_start();	
	?>
		<div class="wrap">
            <h2>Daniel Esparza</h2>
            <p>Consultoría en servicios y soluciones de entorno web.<br>¿Qué tipo de servicio o solución necesita tu negocio?</p>
            <h4>Contact info:</h4>
            <p>
                Sitio web: <a href="https://danielesparza.studio/" target="_blank">https://danielesparza.studio/</a><br>
                Contacto: <a href="mailto:hi@danielesparza.studio" target="_blank">hi@danielesparza.studio</a><br>
                Messenger: <a href="https://www.messenger.com/t/danielesparza.studio" target="_blank">enviar mensaje</a><br>
                Información acerca del plugin: <a href="https://danielesparza.studio/order-sms-notification/" target="_blank">sitio web del plugin</a><br>
                Daniel Esparza | Consultoría en servicios y soluciones de entorno web.<br>
                ©2020 Daniel Esparza, inspirado por #openliveit #dannydshore
            </p>
		</div>
	<?php 
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string;
	}
    }	
    
    add_action( 'admin_enqueue_scripts', 'dewp_register_adminstyle' );
    function dewp_register_adminstyle() {
        wp_register_style( 'dewp_register_adminstyle', plugin_dir_url( __FILE__ ) . '../css/dewp_style_admin.css', array(), '1.0' );
        wp_enqueue_style( 'dewp_register_adminstyle' );
    }
    
}

if ( !function_exists( 'dewp_sms_register_settings' ) ) {
    add_action( 'admin_init', 'dewp_sms_register_settings' );
    function dewp_sms_register_settings (){
        register_setting( 'dewp_settings_group' , 'dewp_settings' );
    }
}

if ( !function_exists( 'dewp_sms_admin_add' ) ) {
	
	add_action( 'admin_menu', 'dewp_sms_admin_add' );
	function dewp_sms_admin_add() {
		add_submenu_page( 'desparza-menu', 'Order SMS Notification', 'Order SMS Notification', 'manage_options', 'dewp-sms-admin-settings', 'dewp_sms_admin_settings' );
	}
    
	function dewp_sms_admin_settings(){
		global $wpdb;
        global $dewp_options;
        ob_start();
		
		if( isset($_GET['settings-updated']) ) {
        ?>
            <div id="message" class="updated" style="margin:15px 15px 0 0;">
            <p><strong><?php _e('Settings saved.') ?></strong></p>
            </div>
		<?php }

		if ( isset($_REQUEST['dewp_settings[enable_sms]']) ){
			if ( ! wp_verify_nonce(  $_REQUEST['dewp_noncefield'], 'dewp_nonceaction' ) ) {
				wp_die( "Error - Verificación nonce no válida" );
			} else {
				update_option( $data['dewp_settings[enable_sms]'] = sanitize_text_field( $_REQUEST['dewp_settings[enable_sms]'] ) );
				update_option( $data['dewp_settings[sid_sms]'] = sanitize_hex_color( $_REQUEST['dewp_settings[sid_sms]'] ) );
				update_option( $data['dewp_settings[token_sms]'] = sanitize_text_field( $_REQUEST['dewp_settings[token_sms]'] ) );
				update_option( $data['dewp_settings[shop_name_sms]'] = sanitize_text_field( $_REQUEST['dewp_settings[shop_name_sms]'] ) );
				update_option( $data['dewp_settings[shop_sms]'] = sanitize_text_field( $_REQUEST['dewp_settings[shop_sms]'] ) );
				$wpdb->insert( 'dewp_noncedata' , $data );
			}
		}
    	?>

        <h2><?php _e( 'Order SMS Notification', 'text-dewp' ); ?></h2>

        <ul>
            <li><strong>= Antes de comenzar (Prerrequisitos): =</strong></li>
            <li>• Tener cuenta de Twilio | <a href="https://www.twilio.com/try-twilio/" target="_blank">Crear cuenta</a>.</li>
            <li>• Twilio API Keys | <a href="https://www.youtube.com/watch?v=Kcnplo9Z_F4/" target="_blank">Ver video</a>.</li>
            <li>• Twilio API para SMS | <a href="https://www.youtube.com/watch?v=GTDAl71V37A/" target="_blank">Ver video</a>.</li>
            <li>• Documentación | <a href="https://www.twilio.com/docs/sms/quickstart/php-manual-install/" target="_blank">Comenzar</a>.</li>
        </ul>

        <ul>
            <li><strong>= Instrucciones básicas: =</strong></li>
            <li>• Habilitar el Plugin.</li>
            <li>• Ingresar las llaves de Twilio.</li>
            <li>• Completar los campos del perfil de la tienda.</li>
        </ul>

		
        <form id="dewp_settings_form" action="options.php" method="post">
            
            <?php settings_fields( 'dewp_settings_group' ); ?>

            <p>
                <input id="dewp_settings[enable_sms]" name="dewp_settings[enable_sms]" type="checkbox" value="1" <?php esc_attr( checked( '1' , isset($dewp_options['enable_sms']) )); ?>  />
                <label for="dewp_settings[enable_sms]"><?php _e( 'Habilitar Order Message Notification', 'text-dewp' ); ?></label>
            </p>
            
			<p>
				<strong><?php _e( 'Configuración del Plugin', 'text-dewp' ); ?></strong>
			</p>
			
			<div class="dewp_settings-block">
				<p><strong>Llaves de Twilio</strong></p>
                <label for="dewp_settings[sid_sms]"><?php _e( 'sid_sms:', 'text-dewp' ); ?></label>
				<input id="dewp_settings[sid_sms]" name="dewp_settings[sid_sms]" type="text" value="<?php echo esc_attr( $dewp_options['sid_sms'] ); ?>" />
				<label for="dewp_settings[token_sms]"><?php _e( 'token_sms:', 'text-dewp' ); ?></label>
				<input id="dewp_settings[token_sms]" name="dewp_settings[token_sms]" type="text" value="<?php echo esc_attr( $dewp_options['token_sms'] ); ?>" />
				<p><strong>Perfil de la tienda</strong></p>
				<label for="dewp_settings[shop_name_sms]"><?php _e( 'Nombre de la tienda', 'text-dewp' ); ?></label>
				<input id="dewp_settings[shop_name_sms]" name="dewp_settings[shop_name_sms]" type="text" value="<?php echo esc_attr( $dewp_options['shop_name_sms'] ); ?>" />
				<label for="dewp_settings[shop_sms]"><?php _e( 'Remitente SMS ( prefijo + número)', 'text-dewp' ); ?></label>
				<input id="dewp_settings[shop_sms]" name="dewp_settings[shop_sms]" type="text" value="<?php echo esc_attr( $dewp_options['shop_sms'] ); ?>" />
                <p>Administrar los números de teléfono | <a href="https://www.twilio.com/console/phone-numbers/getting-started" target="_blank">Twilio Sandbox Number (Agregar número para pruebas)</a></p>
                <p>Administrar los números para pruebas | <a href="https://www.twilio.com/console/phone-numbers/verified" target="_blank">Verified Caller IDs (Agregar números para pruebas)</a></p>
			</div>
			
            <p>
				<button type="submit" name="submit" form="dewp_settings_form" value="Submit"><?php _e( 'Guardar', 'text-dewp' ); ?></button>
				<?php wp_nonce_field( 'dewp_nonceaction', 'dewp_noncefield' ); ?>
			</p>
            
        </form>

    	<?php 
		$output_string = ob_get_contents();
		ob_end_clean();
		echo $output_string;    
	}
	
}