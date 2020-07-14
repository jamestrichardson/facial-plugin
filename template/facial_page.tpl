{* <!-- load CSS files --> *}
{combine_css id="facial" path=$FACIAL_PATH|cat:"template/style.css"}

{* <!-- load JS files --> *}
{* {combine_script id="facial" require="jquery" path=$FACIAL_PATH|cat:"template/script.js"} *}

{* <!-- add inline JS --> *}
{footer_script require="jquery"}
  jQuery('#facial').on('click', function(){
    alert('{'Hello world!'|translate}');
  });
{/footer_script}

{* <!-- add inline CSS --> *}
{html_style}
  #facial{
    display:block;
  }
{/html_style}


{* <!-- add page content here --> *}
<h1>{'What Facial can do for me?'|translate}</h1>

<blockquote>
  {$INTRO_CONTENT}
</blockquote>

<div id="facial">{'Click for fun'|translate}</div>
