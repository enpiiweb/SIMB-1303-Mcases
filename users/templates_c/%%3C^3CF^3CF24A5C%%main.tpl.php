<?php /* Smarty version 2.6.6, created on 2012-11-15 19:18:44
         compiled from /home/mcases/public_html/templates/main.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Magistrates Cases</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
style/style.css" rel="stylesheet" type="text/css" />
<?php echo '
<style type="text/css">
<!--.style1 {color: #0000FF}-->.style2 {color: #666666}</style>
'; ?>


</head>

<body bgcolor="#FFFFFF">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">  

<tr>    

<td valign="top">

<table id="Table_01" width="800" border="0" cellpadding="0" cellspacing="0">      

<tr>          <td colspan="2"><a href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
index.php"><img src="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
images/MCheader_01.gif" alt="Magistrates Cases" width="800" height="186" border="0" id="MCheader_01" /></a><br>            <a href="/index.php"><img src="../images/buttonhome.gif" width="88" height="40" border="0"></a><a href="/history.php"><img src="../images/buttonhistory.gif" width="88" height="40" border="0"></a><a href="/boundcopies.php"><img src="../images/buttonbound.gif" width="88" height="40" border="0"></a><a href="/quoteworthy.php"><img src="../images/buttonquotes.gif" width="88" height="40" border="0"></a><a href="/acknowledge.php"><img src="../images/buttonacknow.gif" width="88" height="40" border="0"></a><a href="/casesum.php"><img src="../images/buttoncasesum.gif" width="88" height="40" border="0"></a><a href="/articles.php"><img src="../images/buttonarticles.gif" width="88" height="40" border="0"></a><a href="/disclaimer.php"><img src="../images/buttondisclaimer.gif" width="88" height="40" border="0"></a><a href="/terms.php"><img src="../images/buttonterms.gif" width="88" height="40" border="0"></a><img src="../images/filler.gif" width="8" height="40"></td>      </tr>      <tr>        <td width="187" valign="top" bgcolor="#FFFFFF" class="darkbg style2">               <p> <form action="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
search.php?action=search" method="post">              <strong>Search:</strong><br />              <input name="search_all" type="text" size="15" /><br />        <input type="submit" value="search" />        </form></p><p>        <a href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
search.php?action=form">Advanced Search</a><br>        

<?php if (GetAuth ( ) == false): ?>
    <a href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
users/index.php">Login</a>
<?php else: ?>
    <a href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
users/index.php">User Panel</a><br/>
    <a href="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
users/logout.php">Log out</a>
<?php endif; ?>

</p>        <p class="style2"><strong>Browse Cases:</strong><br />        <form action="<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
search.php" method="get"><select name="search_catonly"><?php if (count($_from = (array)$this->_tpl_vars['categories'])):
    foreach ($_from as $this->_tpl_vars['cat']):
?>                  <option onclick="location.href = '<?php echo $this->_tpl_vars['smartyglobal']['relroot']; ?>
search.php?action=search&search_catonly=<?php echo $this->_tpl_vars['cat']['id']; ?>
';" value="<?php echo $this->_tpl_vars['cat']['id']; ?>
"><?php echo $this->_tpl_vars['cat']['name']; ?>
</option>        <?php endforeach; unset($_from); endif; ?></select><input type="hidden" name="action" value="search" /><input type="submit" value="Go" /></form></p><p>&nbsp;</p></td>        <td width="613" valign="top" class="mainbody">	  <?php if (isset ( $this->_tpl_vars['main_displaydata'] )):  echo $this->_tpl_vars['main_displaydata'];  endif;  if (isset ( $this->_tpl_vars['main_displayfile'] )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['main_displayfile'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>      <div id="footer" align="right"><br /><br />            </div></td></tr>    </table></td>  </tr></table></body></html>