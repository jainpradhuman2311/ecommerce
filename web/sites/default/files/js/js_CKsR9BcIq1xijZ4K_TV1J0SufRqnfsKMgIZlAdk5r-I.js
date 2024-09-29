/* @license GPL-2.0-or-later https://www.drupal.org/licensing/faq */
((Drupal)=>{Drupal.AjaxCommands.prototype.updateProductUrl=(ajax,response)=>{const params=new URLSearchParams(window.location.search);params.set('v',response.variation_id);window.history.replaceState({},document.title,`${window.location.pathname}?${params.toString()}`);};})(Drupal);;
