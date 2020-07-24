$(function(){
    'use strict';

   // Switch Between Loogin Signup bye5fili w byzheri login signup
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    })
    //Trigger the SELECT bOX iT
    $("select").selectBoxIt({
        autoWidth:false
    });

    $('[placeholder]').focus(function (){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    }).blur(function (){

        $(this).attr('placeholder', $(this).attr('data-text'));
    });
    //Add Astrisk On Required Field
    $('input').each(function(){
        if($(this).attr('required')==='required'){
            $(this).after('<span class ="asterisk">*</span>');
        }
    }); 
   
    //confirmation Message On Button
    $('.confirm').click(function(){
        return confirm('Are You Sure?');
    });
    // haydi function bt5alini ectoub b2albe l card
    $('.live-name').keyup(function(){
      $('.live-preview .card-body h4').text($(this).val());
    });

    $('.live-desc').keyup(function(){
        $('.live-preview .card-body p').text($(this).val());
      });

      $('.live-price').keyup(function(){
        $('.live-preview  .price-tag').text($(this).val()+'$');
      });
 
});