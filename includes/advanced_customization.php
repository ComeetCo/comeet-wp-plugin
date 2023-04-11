<?php
$options = $this->get_options();
?>
<style>
    .comeet_advanced_customization_container_wrapper{
        display: flex;
        width: 100%;
        height: auto;
        flex-wrap: wrap;
    }
  .comeet_advanced_customization_container{
      border: solid 1px rgba(0,0,0,.1);
      display: flex;
      flex-direction: column;
      margin-right: 20px;
      margin-bottom: 20px;
      padding: 20px;
      padding-bottom: 0;
      min-width: 300px;
      min-height: 175px;
  }
  .comeet_advanced_customization_container label
  {
      vertical-align: top;
      text-align: left;
      padding: 20px 10px 20px 0;
      width: 290px;
      min-height: 35px;
      line-height: 1.3;
      font-weight: 600;
      margin-bottom: 10px;
      display: block;
  }
  .comeet_social_sharing_main_options{
      width: 100%;
      margin-bottom: 20px;
  }
  .comeet_social_sharing_main_options select{
      min-width: 290px;
  }
  .border-pulse-addition{
      animation: border-pulsate 0.2s;
      animation-iteration-count: 5;
  }
    @keyframes border-pulsate {
        0%   { border-color: rgb(0, 21, 255); }
        50%  { border-color: rgba(0, 255, 255, 0); }
        100% { border-color: rgba(0, 21, 255, 1); }
    }
</style>
<?php
print_r($options);
?>
<div class="card" style="margin-bottom: 4em;max-width: unset;">
    <p>Advanced customization options for the <a href="https://developers.comeet.com/reference/application-form-widget">Application form widget</a> and the <a href="https://developers.comeet.com/reference/social-widget">Social widget</a> - <a href="https://developers.comeet.com/reference/embedding-widgets">Learn More</a>
    </p>
    <h3>Application form advanced customization</h3>
    <div class="comeet_advanced_customization_container_wrapper">
        <div class="comeet_advanced_customization_container">
            <label for="apply-as-employee">
                Allow employees to apply to open positions?
            </label>
            <select name="<?php echo $this->db_opt;?>[apply-as-employee]" id="comeet_apply_as_employee">
                <option <?php echo ($options['comeet_apply_as_employee']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_apply_as_employee']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,apply%2Das%2Demployee,-When%20enabled%2C%20employees">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_email_required">
                Is email required in application form?
            </label>
            <select name="<?php echo $this->db_opt;?>[field-email-required]" id="comeet_field_email_required">
                <option <?php echo ($options['comeet_field_email_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_email_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Demail%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_phone_required">
                Is phone number required in application form?
            </label>
            <select name="<?php echo $this->db_opt;?>[field-phone-required]" id="comeet_field_phone_required">
                <option <?php echo ($options['comeet_field_phone_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_phone_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dphone%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_resume">
                Is resume required in application form?
            </label>
            <select name="<?php echo $this->db_opt;?>[field-resume]" id="comeet_field_resume">
                <option <?php echo ($options['comeet_field_resume']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_resume']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dresume,-Show%20the%20option">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_linkedin">
                Enable LinkedIn?
            </label>
            <select name="<?php echo $this->db_opt;?>[field-linkedin]" id="comeet_field_linkedin">
                <option <?php echo ($options['comeet_field_linkedin']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_linkedin']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dlinkedin,-Enable%20LinkedIn%3F%20Defaults">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_require_profile">
                Which profile is required?
            </label>
            <select name="<?php echo $this->db_opt;?>[require-profile]" id="comeet_require_profile">
                <option <?php echo ($options['comeet_require_profile'] == 'resume') ? "selected=\"selected\"" : ""?> value="resume">Resume</option>
                <option <?php echo ($options['comeet_require_profile'] == 'linkedin') ? "selected=\"selected\"" : ""?>value="linkedin">LinkedIn</option>
                <option <?php echo ($options['comeet_require_profile'] == 'resume-linkedin') ? "selected=\"selected\"" : ""?>value="resume-linkedin">Resume and LinkedIn</option>
                <option <?php echo ($options['comeet_require_profile'] == 'any') ? "selected=\"selected\"" : ""?>value="any">Any</option>
                <option <?php echo ($options['comeet_require_profile'] == 'none') ? "selected=\"selected\"" : ""?>value="none">None</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,require%2Dprofile,-Which%20profile%20is">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_website">
                Show website field?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_website]" id="comeet_field_website">
                <option <?php echo ($options['comeet_field_website']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_website']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dwebsite,-Show%20website%20field">click here</a>
        </div>

        <div class="comeet_advanced_customization_container comeet_field_website_required" <?php echo (!$options['comeet_field_website']) ? "style=\"display: none;\"" : ""?>>
            <label for="comeet_field_website_required">
                Require website?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_website_required]" id="comeet_field_website_required">
                <option <?php echo ($options['comeet_field_website_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_website_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dwebsite%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_coverletter">
                Show cover letter field?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_coverletter]" id="comeet_field_coverletter">
                <option <?php echo ($options['comeet_field_coverletter']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_coverletter']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dcoverletter,-Show%20cover%20letter">click here</a>
        </div>

        <div class="comeet_advanced_customization_container comeet_field_coverletter_required" <?php echo (!$options['comeet_field_coverletter']) ? "style=\"display: none;\"" : ""?>>
            <label for="comeet_field_coverletter_required">
                Require cover letter?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_coverletter_required]" id="comeet_field_coverletter_required">
                <option <?php echo ($options['comeet_field_coverletter_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_coverletter_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dcoverletter%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_portfolio">
                Show portfolio field?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_portfolio]" id="comeet_field_portfolio">
                <option <?php echo ($options['comeet_field_portfolio']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_portfolio']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dportfolio,-Show%20portfolio%20field">click here</a>
        </div>

        <div class="comeet_advanced_customization_container comeet_field_portfolio_required" <?php echo (!$options['comeet_field_portfolio']) ? "style=\"display: none;\"" : ""?>>
            <label for="comeet_field_portfolio_required">
                Require portfolio?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_portfolio_required]" id="comeet_field_portfolio_required">
                <option <?php echo ($options['comeet_field_portfolio_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_portfolio_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dportfolio%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_field_personalnote">
                Show personal note field?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_personalnote]" id="comeet_field_personalnote">
                <option <?php echo ($options['comeet_field_personalnote']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_personalnote']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,field%2Dpersonalnote,-Show%20personal%20note">click here</a>
        </div>

        <div class="comeet_advanced_customization_container comeet_field_personalnote_required" <?php echo (!$options['comeet_field_personalnote']) ? "style=\"display: none;\"" : ""?>>
            <label for="comeet_field_personalnote_required">
                Require personal note?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_field_personalnote_required]" id="comeet_field_personalnote_required">
                <option <?php echo ($options['comeet_field_personalnote_required']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_field_personalnote_required']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dpersonalnote%2Drequired">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_button_color">
                Application form Submit button color
            </label>
            <input type="text" placeholder="#167acd" name="<?php echo $this->db_opt;?>[comeet_button_color]" id="comeet_button_color" value="<?php echo $options['comeet_button_color']?>" />
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,button%2Dcolor,-Color%20for%20the">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_button_text">
                Application form Submit button text
            </label>
            <input type="text" placeholder="Submit Application" name="<?php echo $this->db_opt;?>[comeet_button_text]" id="comeet_button_text" value="<?php echo $options['comeet_button_text'];?>" />
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,button%2Dtext,-Text%20to%20display">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_font_size">
                Application form font size
            </label>
            <input type="text" placeholder="13px" name="<?php echo $this->db_opt;?>[comeet_font_size]" id="comeet_font_size" value="<?php echo $options['comeet_font_size'];?>" />
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,font%2Dsize,-Font%2Dsize%20to">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_button_font_size">
                Application form submit button font size
            </label>
            <input type="text" placeholder="13px" name="<?php echo $this->db_opt;?>[comeet_button_font_size]" value="<?php echo $options['comeet_button_font_size'];?>" id="comeet_button_font_size" />
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=optional-,button%2Dfont%2Dsize,-Font%2Dsize%20for">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_labels_position">
                Where to place the labels?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_labels_position]" id="comeet_labels_position">
                <option <?php echo ($options['comeet_labels_position'] == 'responsive') ? "selected=\"selected\"" : ""?> value="responsive">Responsive</option>
                <option <?php echo ($options['comeet_labels_position'] == 'left') ? "selected=\"selected\"" : ""?> value="left">Left</option>
                <option <?php echo ($options['comeet_labels_position'] == 'top') ? "selected=\"selected\"" : ""?> value="top">Top</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/application-form-widget#:~:text=field%2Dpersonalnote%2Drequired">click here</a>
        </div>


    </div>
    <hr/>
    <h3>Social widget advanced customization</h3>
    <div class="comeet_social_sharing_main_options">
        <label for="comeet_social_sharing_on_careers">
            Show social sharing widget on Careers page?
        </label><br />
        <select name="<?php echo $this->db_opt;?>[comeet_social_sharing_on_careers]" id="comeet_social_sharing_on_careers">
            <option <?php echo ($options['comeet_social_sharing_on_careers']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
            <option <?php echo (!$options['comeet_social_sharing_on_careers']) ? "selected=\"selected\"" : ""?> value="false">No</option>
        </select>
    </div>
    <div class="comeet_social_sharing_main_options">
        <label for="comeet_social_sharing_on_positions">
            Show social sharing widget on position pages?
        </label><br />
        <select name="<?php echo $this->db_opt;?>[comeet_social_sharing_on_positions]" id="comeet_social_sharing_on_positions">
            <option <?php echo ($options['comeet_social_sharing_on_positions']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
            <option <?php echo (!$options['comeet_social_sharing_on_positions']) ? "selected=\"selected\"" : ""?> value="false">No</option>
        </select>
    </div>
    <div class="comeet_advanced_customization_container_wrapper">
        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_pinterest">
                Show the Pinterest button?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_social_pinterest]" id="comeet_social_pinterest">
                <option <?php echo ($options['comeet_social_pinterest']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_social_pinterest']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=Required-,social%2Dpinterest,-Show%20the%20Pinterest">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_whatsapp">
                Show the WhatsApp button?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_social_whatsapp]" id="comeet_social_whatsapp">
                <option <?php echo ($options['comeet_social_whatsapp']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_social_whatsapp']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=optional-,social%2Dwhatsapp,-Show%20the%20WhatsApp">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_employees">
                Allow employees to authenticate?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_social_employees]" id="comeet_social_employees">
                <option <?php echo ($options['comeet_social_employees']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_social_employees']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=optional-,social%2Demployees,-Enable%20support%20for">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_show_title">
                Show a title?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_social_show_title]" id="comeet_social_show_title">
                <option <?php echo ($options['comeet_social_show_title']) ? "selected=\"selected\"" : ""?> value="true">Yes</option>
                <option <?php echo (!$options['comeet_social_show_title']) ? "selected=\"selected\"" : ""?> value="false">No</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=optional-,social%2Dshow%2Dtitle,-Show%20a%20title">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_share_url">
                Override the URL to share.
            </label>
            <input type="text" placeholder="" name="<?php echo $this->db_opt;?>[comeet_social_share_url]" id="comeet_social_share_url" value="<?php echo $options['comeet_social_share_url'];?>" />
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=optional-,social%2Dshare%2Durl,-Override%20the%20URL">click here</a>
        </div>

        <div class="comeet_advanced_customization_container">
            <label for="comeet_social_color">
                Which colors to use for social buttons?
            </label>
            <select name="<?php echo $this->db_opt;?>[comeet_social_color]" id="comeet_social_color">
                <option <?php echo ($options['comeet_social_color'] == 'white') ? "selected=\"selected\"" : ""?> value="white">White</option>
                <option <?php echo ($options['comeet_social_color'] == 'native') ? "selected=\"selected\"" : ""?> value="native">Native</option>
            </select>
            <br />
            For more information <a target="_blank" href="https://developers.comeet.com/reference/social-widget#:~:text=optional-,social%2Dcolor,-Which%20colors%20to">click here</a>
        </div>



    </div>
</div>
<script>
    jQuery(document).ready(function($){
        //website field and required
        console.log($('#comeet_field_website :selected').val());
        if(!$('#comeet_field_website :selected').val()){
            $('#comeet_field_website_required').val('false');
            $('#comeet_field_website_required').attr('disabled', true);
        }
        $('#comeet_field_website').change(function(){
           if($(this).val() == 'true'){
               $('.comeet_field_website_required').show().addClass('border-pulse-addition');
               $('#comeet_field_website_required').attr('disabled', false);
           } else {
               $('.comeet_field_website_required').hide().removeClass('border-pulse-addition');
               $('#comeet_field_website_required').val('false');
               $('#comeet_field_website_required').attr('disabled', true);
           }
        });

        //cover letter field and required
        if($('#comeet_field_coverletter :selected').val() == "false"){
            $('#comeet_field_coverletter_required').val('false');
            $('#comeet_field_coverletter_required').attr('disabled', true);
        }
        $('#comeet_field_coverletter').change(function(){
            if($(this).val() == 'true'){
                $('.comeet_field_coverletter_required').show().addClass('border-pulse-addition');
                $('#comeet_field_coverletter_required').attr('disabled', false);
            } else {
                $('.comeet_field_coverletter_required').hide().removeClass('border-pulse-addition');
                $('#comeet_field_coverletter_required').val('false');
                $('#comeet_field_coverletter_required').attr('disabled', true);
            }
        });

        //Portfolio letter field and required
        if($('#comeet_field_portfolio :selected').val() == "false"){
            $('#comeet_field_portfolio_required').val('false');
            $('#comeet_field_portfolio_required').attr('disabled', true);
        }
        $('#comeet_field_portfolio').change(function(){
            if($(this).val() == 'true'){
                $('.comeet_field_portfolio_required').show().addClass('border-pulse-addition');
                $('#comeet_field_portfolio_required').attr('disabled', false);
            } else {
                $('.comeet_field_portfolio_required').hide().removeClass('border-pulse-addition');
                $('#comeet_field_portfolio_required').val('false');
                $('#comeet_field_portfolio_required').attr('disabled', true);
            }
        });

        //Personal Note letter field and required
        if($('#comeet_field_personalnote :selected').val() == "false"){
            $('#comeet_field_personalnote_required').val('false');
            $('#comeet_field_personalnote_required').attr('disabled', true);
        }
        $('#comeet_field_personalnote').change(function(){
            if($(this).val() == 'true'){
                $('.comeet_field_personalnote_required').show().addClass('border-pulse-addition');
                $('#comeet_field_personalnote_required').attr('disabled', false);
            } else {
                $('.comeet_field_personalnote_required').hide().removeClass('border-pulse-addition');
                $('#comeet_field_personalnote_required').val('false');
                $('#comeet_field_personalnote_required').attr('disabled', true);
            }
        });
    });
</script>
<?php
