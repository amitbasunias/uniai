$(document).ready(function () {
	$(".sub_price").hide();
	$(".reg_price").hide();
	$(".opt").on("click", function () {
		// Remove all instances of selected
		$(".opt").removeClass("selected");
		// collect information to pass to form
		var rel = $(this).data("rel");
		var pri = $(this).data("price");
		//add class to selected
		$('[data-rel="' + rel + '"]').addClass("selected");

		// Update the hidden form
		$("#in_opt").val(rel);
		$("#in_total").val(pri);

		// Update the pricing at top
		if (rel == 1) {
			$(".reg_price").show();
			$(".sub_price").hide();
		} else {
	
			$(".sub_price").show();
			$(".reg_price").hide();
		}
	});

	$(".freq_drop").on("click", function () {
		$(".dropout").slideToggle();
	});

	$(".freq_opt").on("click", function () {
		var txt = $("b", this).html();
		var rel = $(this).data("rel");
		$(".freq_selected b").html(txt);
		$("#in_freq").val(rel);
	});

	$("#sel_q").on("change", function () {
		var quan = $("#sel_q option:selected").val();
		$("#in_quan").val(quan);
		console.log(quan);
	});
});