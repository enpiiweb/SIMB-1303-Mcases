<h2>{$articlelist_heading}</h2><br>
{foreach from=$articlelist_articles item=article}
<div class="article">
<div class="title">{$article->title}</div><div class="date">{$article->day}/{$article->month}/{$article->year}</div><br clear="all">
<div class="ref">Reference: {$article->caseid}/{$article->courtid}</div><div class="link"><a href="viewarticle.php?action=viewdetails&id={$article->id}">View Details</a></div><br clear="all">
<div class="sampleText"><i>{$article->sample}</i></div>
</div>
{/foreach}

<div class="sampleText">
{$search_results}
</div>




