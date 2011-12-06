<?php

function rex_linkmap_backlink($id, $name)
{
  return 'javascript:insertLink(\'redaxo://'.$id.'\',\''.addSlashes($name).'\');';
}

function rex_linkmap_format_label(rex_ooRedaxo $OOobject)
{
  $label = $OOobject->getName();

  if(trim($label) == '')
    $label = '&nbsp;';

  if (rex::getUser()->hasPerm('advancedMode[]'))
    $label .= ' ['. $OOobject->getId() .']';

  if(rex_ooArticle::isValid($OOobject) && !$OOobject->hasTemplate())
    $label .= ' ['.rex_i18n::msg('lmap_has_no_template').']';

  return $label;
}

function rex_linkmap_format_li(rex_ooRedaxo $OOobject, $current_category_id, rex_context $context, $liAttr = '', $linkAttr = '')
{
  $liAttr .= $OOobject->getId() == $current_category_id ? ' id="rex-linkmap-active"' : '';
  $linkAttr .= ' class="'. ($OOobject->isOnline() ? 'rex-online' : 'rex-offine'). '"';

  if(strpos($linkAttr, ' href=') === false)
    $linkAttr .= ' href="'. $context->getUrl(array('category_id' => $OOobject->getId())) .'"';

  $label = rex_linkmap_format_label($OOobject);

  return '<li'. $liAttr .'><a'. $linkAttr .'>'. htmlspecialchars($label) . '</a>';
}

function rex_linkmap_tree(array $tree, $category_id, array $children, rex_context $context)
{
  $ul = '';
  if(is_array($children))
  {
    $li = '';
    $ulclasses = '';
    if (count($children)==1) $ulclasses .= 'rex-children-one ';
    foreach($children as $cat){
      $cat_children = $cat->getChildren();
      $cat_id = $cat->getId();
      $liclasses = '';
      $linkclasses = '';
      $sub_li = '';
      if (count($cat_children)>0) {
        $liclasses .= 'rex-children ';
        $linkclasses .= 'rex-linkmap-is-not-empty ';
      }

      if (next($children)== null ) $liclasses .= 'rex-children-last ';
      $linkclasses .= $cat->isOnline() ? 'rex-online ' : 'rex-offline ';
      if (is_array($tree) && in_array($cat_id,$tree))
      {
        $sub_li = rex_linkmap_tree($tree, $cat_id, $cat_children, $context);
        $liclasses .= 'rex-active ';
        $linkclasses .= 'rex-active ';
      }

      if($liclasses != '')
        $liclasses = ' class="'. rtrim($liclasses) .'"';

      if($linkclasses != '')
        $linkclasses = ' class="'. rtrim($linkclasses) .'"';

      $label = rex_linkmap_format_label($cat);

      $li .= '      <li'.$liclasses.'>';
      $li .= '<a'.$linkclasses.' href="'. $context->getUrl(array('category_id' => $cat_id)).'">'.htmlspecialchars($label).'</a>';
      $li .= $sub_li;
      $li .= '</li>'. "\n";
    }

    if($ulclasses != '')
      $ulclasses = ' class="'. rtrim($ulclasses) .'"';

    if ($li!='') $ul = '<ul'.$ulclasses.'>'."\n".$li.'</ul>'. "\n";
  }
  return $ul;
}
