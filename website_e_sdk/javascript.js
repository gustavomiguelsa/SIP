	

	$(".hover").mouseleave(
		function () {
			$(this).removeClass("hover");
		}
	);

	$("figure").mouseleave(
		function () {
			$(this).removeClass("hover");
		}
	);

	/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
	function myFunction() {
		var x = document.getElementById("myTopnav");
		if (x.className === "topnav") {
			x.className += " responsive";
		} else {
			x.className = "topnav";
		}
	}

	$(document).ready(function(){
		$("#flip").hover(function(){
			//$("#panel").slideRight("slow");
			$("#panel").toggle("slide", { direction: "left" }, 1000);
		});
	});

