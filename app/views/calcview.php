<? include 'txt/calc_instruction.php'; ?>
<script src="/js/ajax_get_calc.js" type="text/javascript"></script>
<h3><? echo L_CALC_DERIVATIVE; ?></h3>

<div id="query">
    <table>
        <tr><td class="rightcol"><?echo L_QUERY; ?>:</td><td><input class="str" type='text' value='d(x^2-y+6)/d(x)' name='str' maxlength="128"></td></tr>
        <tr><td class="center_button" colspan="2"><input type='submit' onclick="submitInputData()" value="<? echo L_SUBMIT; ?>"></td></tr>
    </table>
    <div id="processing"><img src="/img/loading.gif"></div>
    <div id="results"></div>
    <div id="fails"></div>
	<div id="graphic"><img class="math_plot"></div>
</div>