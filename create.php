<script>
function checkform()
{
	var x = document.forms["theCreateForm"]["question"].value;
	if(x == null || x == "")
	{
		alert("Please enter a question");
		return false;
	}
	var y = document.forms["theCreateForm"]["choice1"].value;
	var z = document.forms["theCreateForm"]["choice2"].value;
	if(y == null || y == "" || z == null || z == "")
	{
		alert("Please enter at least two choices");
		return false;
	}
	return confirm("Submit data?");
}
</script>

	<h1>Create a new poll</h1>

	<form name="theCreateForm" target="_top" onSubmit="return checkform()" action="<? echo fb_root("index.php"); ?>" method="get">
	<input name="respond" type="hidden" value="CREATE" />
	<input name="date" type="hidden" value="<? echo date('Y-m-d'); ?>" />
	<h2>Question</h2>
	<textarea name="question" rows="4" cols="80"></textarea>
	<input class="button" value="Create Poll" type="submit" />		

	<table><tr>
	<th>Display identity?</th>
	<th>Display vote?</th>
	<th>Vote multiple choices?</th></tr>
	<tr><td>
	<input type="radio" name="show_id" value="1"> Yes
	<input type="radio" name="show_id" value="0" checked> No
	</td><td>
	<input type="radio" name="show_vote" value="1"> Yes
	<input type="radio" name="show_vote" value="0" checked> No
	</td><td>
	<input type="radio" name="select_multiple" value="1"> Yes
	<input type="radio" name="select_multiple" value="0" checked> No
	</td></tr></table>
	
	<h3>Choice 1:</h3>
	<input name="choice1" value="" type="text" size="80" />
	<h3>Choice 2:</h3>
	<input name="choice2" value="" type="text" size="80" />
	<h3>Choice 3:</h3>
	<input name="choice3" value="" type="text" size="80" />
	<h3>Choice 4:</h3>
	<input name="choice4" value="" type="text" size="80" />
	<h3>Choice 5:</h3>
	<input name="choice5" value="" type="text" size="80" />
	<h3>Choice 6:</h3>
	<input name="choice6" value="" type="text" size="80" />
	<h3>Choice 7:</h3>
	<input name="choice7" value="" type="text" size="80" />
	<h3>Choice 8:</h3>
	<input name="choice8" value="" type="text" size="80" />
	<h3>Choice 9:</h3>
	<input name="choice9" value="" type="text" size="80" />
	<h3>Choice 10:</h3>
	<input name="choice10" value="" type="text" size="80" />

	</form>				
