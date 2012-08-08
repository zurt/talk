<?php

/**
 * Modifies the auto_link helper (url_helper) by accepting as an optional third
 * argument an array of html attributes for the anchor tags (just like the anchor helper).
 *
 * This array is supplied as the third argument, replacing the
 * optional argument $pop in the original helper.
 * 
 * This modified helper attempts to be backward compatible with the use of the 
 * original helper by accepting TRUE and FALSE as possible values for the $attributes
 * argument, and giving output identical to the original usage of the helper.
 *
 * use:  auto_link($string, 'url' , array('class' => 'external', 'target'=>'_blank'));
 * use:  auto_link($string, 'email', array('class' => 'email_link' , 'style' => 'color:red;'));
 * use(legacy): auto_link($string, 'url' , TRUE);
 *
 * @see url_helper
 * @link http://codeigniter.com/user_guide/helpers/url_helper.html
 * @param string $str
 * @param string $type 
 * @param mixed $attributes 
 * @return string
 */
function auto_link($str, $type = 'both', $attributes = '')
    {
            // MAKE THE THIRD ARGUMENT BACKWARD COMPATIBLE
            // here we deal with the original third argument $pop
            // which could be TRUE or FALSE, and was FALSE by default.
            $pop = '';
            if ($attributes === TRUE)
            {
                $pop = ' target="_blank" ';
                $attributes = '';
            }
            elseif ($attributes === FALSE)
            {
                $pop = $attributes = '';
            }




        if ($type != 'email')
        {

            if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)c99491ffbb4d1455029b7546e1b1b34864bfd3b8lt;]+)#i", $str, $matches))
            {
                                
                                if( $attributes != '' )
                                {
                                        $attributes = _parse_attributes($attributes);
                                }
                               

                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['6'][$i]))
                    {
                        $period = '.';
                        $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
                    }





                    $str = str_replace($matches['0'][$i],
                                        $matches['1'][$i].'<a  href="http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'">http'.
                                        $matches['4'][$i].'://'.
                                        $matches['5'][$i].
                                        $matches['6'][$i].'</a>'.
                                        $period, $str);
                }
            }
        }

        if ($type != 'url')
        {
            if (preg_match_all("/([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", $str, $matches))
            {
                for ($i = 0; $i < count($matches['0']); $i++)
                {
                    $period = '';
                    if (preg_match("|\.$|", $matches['3'][$i]))
                    {
                        $period = '.';
                        $matches['3'][$i] = substr($matches['3'][$i], 0, -1);
                    }

                    $str = str_replace($matches['0'][$i], safe_mailto($matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i]).$period, $str,$attributes);
                }
            }
        }

        return $str;
    }//END