
function isString(str){ 
    return (typeof str=='string') && str.constructor == String; 
} 

function cutstr(str, len) {
    var str_length = 0;
    var str_len = 0;
    str_cut = new String();
    str_len = isString(str) ? str.length : 0;
    for (var i = 0; i < str_len; i++) {
        a = str.charAt(i);
        str_length++;
        if (escape(a).length > 4) {
            //中文字符的长度经编码之后大于4
            str_length++;
        }
        str_cut = str_cut.concat(a);
        if (str_length >= len) {
            str_cut = str_cut.concat("...");
            return str_cut;
        }
    }
    //如果给定字符串小于指定长度，则返回源字符串；
    if (str_length < len) {
        return str;
    }
}

function changeLayout(){
    var optionObj = document.getElementById("selectid").options;
    for(i = 0; i < optionObj.length; i++){
        var length = optionObj[i].text.length;
        for(j = 0; j < 20-length; j++){
            optionObj[i].text = ' ' + optionObj[i].text;
        }
    }
}


function backhistroy()
{
	try{
		window.android.androidGoBack();
	}catch(e){
	}
    history.go(-1);
}

$(function(){
    var plate=true;
    var home_on=true;

   // 侧导航

    $("#panel").height(document.body.scrollHeight);
    $(".hide_shade").height(document.body.scrollHeight);
    /*$(".wrapper").height($("#wrap").height());*/
    $(".wrapper").height(document.body.clientHeight);

    $(".hide_shade").on("tap",function(){
        $("#wrap").animate({"left":"0"},200);
        $("#panel").animate({"left":"-50%"},200);
        $(".wrapper").css({"overflow":"auto"});
        $(".hide_shade").hide();
        home_on=true;

    });
  $(".home").on("touchend",function(event){
   if(home_on){
       $("#wrap").animate({"left":"50%"},200);
       $("#panel").animate({"left":"0"},200);
       $(".wrapper").css({"overflow":"hidden"});
       $(".hide_shade").show();
        home_on=false;
        return false;
     }else{
      $("#wrap").animate({"left":"0"},200);
      $("#panel").animate({"left":"-50%"},200);
       $(".wrapper").css({"overflow":"auto"});
       $(".hide_shade").hide();
        home_on=true;
        return false;
     }
  });
    // 发布帖子
   $(".issue_main button").on("tap",function(){
     $(".alert").show().height(document.body.scrollHeight);
   })

   // 板块展示
   $(".plate_main>li h2").on("tap",function(){
    if(plate){
       $(this).addClass("plate_list_on").siblings().removeClass("plate_list_on");
        $(this).css({"background":"#eaeaea"});
        $(this).next().show();
        return plate=false;
    }else{
       $(this).removeClass("plate_list_on");
       $(this).css({"background":"#fff"});
       $(this).next().hide();
        return plate=true;
    }
    
   })

     //搜索截取
    var str1=$(".search_details_article p").html();
        $(".search_details_article p").html(cutstr(str1, 110));
     //用户名截取
    var str2=$(".panel_section_main dd").text();
        $(".panel_section_main dd").text(cutstr(str2,5));
    //版块名截取
    var str3=$(".forum_name").html();
        $(".forum_name").html(cutstr(str3,16));
    //推荐发帖人名截取
    $('.recomm_article_main small .small_span').each(function () {
        $(this).html(cutstr($(this).html(),10));
    });
    //个人资料等级截取
    $('.resume_section_main dd .orange em font').each(function () {
        $(this).html(cutstr($(this).html(),14));
    });

   function Focus(){
       $("#focus").hide();
       $(".header_seach").css({"left":"0.72em"});
       $(".abolish").show();
       $(".off_btn").show();
   }
    function Blur(){
        $(".abolish").hide();
        $(".header_seach").css({"left":"3.2em"});
        $("#focus").show();
        $(".off_btn").hide();

    }
    // 搜索  激活状态
    var search_one=true;
	$("#search").focus(function(){
		Focus();
		if(search_one){
			$(".search_details").show();
			//$(".search_general").hide();
			//$(".search_lately").hide();
			search_one=false;
		}
     });
     $(".abolish").on("tap",function(){
         Blur();
     });
	$(".off_btn").on("tap",function(){
	  $("#search").val("");
	});


	$(".nav li").on("tap",function(){
		var a_val = $(this).children("a");
		var tjstr = a_val[0].href;
		var num=tjstr.indexOf("nav_tye");
		var tstr = tjstr.substr(num + 8);

		$(this).addClass("nav_on").siblings().removeClass("nav_on");
		if(tstr == 'newest'){
                    $(".newest").show();
                    $(".hot").hide();
                    $(".elite").hide();
		}else if(tstr == 'hot'){
		  $(".newest").hide();
		  $(".hot").show();
		  $(".elite").hide();
                  $("#page_num_color").text("1");
		}else if(tstr == 'best'){
		  $(".newest").hide();
		  $(".hot").hide();
		  $(".elite").show();
		}else{
                    $(".newest").hide();
                    $(".hot").show();
                    $(".elite").hide();
		}

	});
	
	var jstr = window.location.href;
	var num=jstr.indexOf("nav_tye");
	var str = jstr.substr(num + 8);
	var blastand = jstr.lastIndexOf('&');
	var lastand = str.lastIndexOf('&');
	if(lastand && blastand > num){
		var str = jstr.substr(num+8 ,lastand);
	}
	if(str == 'newest'){
		$(".newest").show();
		$(".hot").hide();
		$(".elite").hide();
		$(".nav_newest").addClass("nav_on");
	}else if(str == 'hot'){
		$(".newest").hide();
		$(".hot").show();
		$(".elite").hide();
		$(".nav_hot").addClass("nav_on");
	}else if(str == 'best'){
		$(".newest").hide();
		$(".hot").hide();
		$(".elite").show();
		$(".nav_elite").addClass("nav_on");
	}else{
		$(".newest").hide();
		$(".hot").show();
		$(".elite").hide();
		$(".nav_hot").addClass("nav_on");
	}
})

