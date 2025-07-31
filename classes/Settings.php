<?php
namespace DanceStudioManager;

class Settings
{
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'Init' ) );
        add_action( 'admin_menu', array( $this, 'InitPage' ) );
    }
    
    public function Init()
    {
        register_setting( 'dsm_api_settings', 'dsm_api_url');
        register_setting( 'dsm_api_settings', 'dsm_api_key');
        register_setting( 'dsm_api_settings', 'dsm_api_version');
        register_setting( 'dsm_api_settings', 'dsm_private_lesson_section');
        register_setting( 'dsm_api_settings', 'dsm_random_url_parameter');
        //register_setting( 'dsm_api_settings', 'dsm_class_cache');
    }
    
    public function InitPage()
    {
        add_options_page(
            'Dance Studion Manager Settings', 
            'DSM Settings', 
            'manage_options', 
            'dsm-settings', 
            array( $this, 'SetPage' )
        );
    }
    
    public function SetPage()
    {

        if (!empty($_POST['clear_cache'])) {
            delete_expired_transients( true );
            $dsm_classes_list = App::GetApi()->GetList("classes/list");
            set_transient( 'dsm_classes_list', $dsm_classes_list, 6 * HOUR_IN_SECONDS );
        }
?>
<div class="wrap">
<h1>DSM Plugin</h1>
<form method="post" action="options.php">
    <?php settings_fields( 'dsm_api_settings' ); ?>
    <?php do_settings_sections( 'dsm_api_settings' ); ?>
    <table class="form-table">
        <tr valign="top" >
        <th scope="row">DSM Url</th>
        <td><input type="text" name="dsm_api_url" value="<?php echo esc_attr( get_option('dsm_api_url') ); ?>" placeholder="https://clients.dancestudiomanager.com/" style="min-width:360px"/></td>
        </tr>
        <tr valign="top">
        <th scope="row">DSM Api Key</th>
        <td><input type="text" name="dsm_api_key" value="<?php echo esc_attr( get_option('dsm_api_key') ); ?>" style="min-width:360px"/></td>
        </tr>
        <tr valign="top">
        <th scope="row">DSM Api Version</th>
            <td>
                <select name="dsm_api_version">
                <?php
                    foreach(Api::GetApiVersionList() as $v):
                        echo '<option value="'.$v.'" '.((get_option('dsm_api_version') == $v) ? 'selected="selected"' : '').'>'.$v.'</option>';
                    endforeach;
                ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">DSM Enable Private Lessons Section</th>
            <td>
                <select name="dsm_private_lesson_section">
                <?php
                    echo '<option value="0" '.((get_option('dsm_private_lesson_section') == '0') ? 'selected="selected"' : '').'>No</option>';
                    echo '<option value="1" '.((get_option('dsm_private_lesson_section') == '1') ? 'selected="selected"' : '').'>Yes</option>';
                ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">DSM Add Random Url Parameter (prevent caching)</th>
            <td>
                <select name="dsm_random_url_parameter">
                <?php
                    echo '<option value="0" '.((get_option('dsm_random_url_parameter') == '0') ? 'selected="selected"' : '').'>No</option>';
                    echo '<option value="1" '.((get_option('dsm_random_url_parameter') == '1') ? 'selected="selected"' : '').'>Yes</option>';
                ?>
                </select>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
    <?php
    }
}