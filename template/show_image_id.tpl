{* Definiciones de variables multiidioma / Multi language variables setup*}
{if $lang_info.code == 'es_AR'}
  {assign var="WHATSAPP_HOVER_TEXT" value="Consultame sobre esta foto por WhatsApp"}
  {assign var="WHATSAPP_MESSAGE" value="Hola Pablo, quiero saber sobre este cuchillo (ID: {$SHOW_IMAGE_ID}): "}
{else}
  {assign var="WHATSAPP_HOVER_TEXT" value="Ask me about this photo via WhatsApp"}
  {assign var="WHATSAPP_MESSAGE" value="Hi Pablo, I want to know about this knife (ID: {$SHOW_IMAGE_ID}): "}
{/if}

{* N?mero de tel?fono (igual para todos los idiomas) *}
{assign var="WHATSAPP_PHONE_NUMBER" value="12345678910"}

{html_style}
#theMainImageContainer {
  position: relative;
  display: inline-block;
}
#theMainImage {
  display: block;
}
#show-image-id {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background-color: #00cc00;
  color: white;
  padding: 2px 5px;
  font-size: 14px;
  z-index: 1000;
  border-radius: 3px;
  opacity: 0.8;
  cursor: pointer;
  display: flex;
  align-items: center;
}
#show-image-id i {
  margin-right: 5px;
}
/* Estilos para el tooltip */
#show-image-id::after {
  content: "{$WHATSAPP_HOVER_TEXT}";
  position: absolute;
  bottom: 100%;
  right: 0;
  background-color: #333;
  color: #fff;
  padding: 5px;
  border-radius: 3px;
  font-size: 12px;
  white-space: nowrap;
  visibility: hidden;
  opacity: 0;
  transition: opacity 0.3s;
}
#show-image-id:hover::after {
  visibility: visible;
  opacity: 1;
}
{/html_style}

{footer_script require='jquery'}
jQuery(document).ready(function(){
  var $img = jQuery("#theMainImage");
  var $container = jQuery('<div id="theMainImageContainer"></div>');
  $img.wrap($container);
  var $idDiv = jQuery('<div id="show-image-id"><i class="fab fa-whatsapp"></i>{$SHOW_IMAGE_ID}</div>');
  $img.parent().append($idDiv);
  
  // Obtener la URL actual de la p?gina
  var currentUrl = window.location.href;
  $idDiv.on('click', function() {
    // Mensaje con el ID de la imagen y el enlace a la foto
    var message = "{$WHATSAPP_MESSAGE}" + currentUrl;
    var whatsappUrl = "https://wa.me/{$WHATSAPP_PHONE_NUMBER}?text=" + encodeURIComponent(message);
    window.open(whatsappUrl, '_blank');
  });
});
{/footer_script}