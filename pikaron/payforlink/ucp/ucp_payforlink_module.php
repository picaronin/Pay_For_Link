<?php
/**
 * PayForLink extension for the phpBB >=3.3.0 Forum Software package.
 * @copyright (c) 2022 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace pikaron\payforlink\ucp;

class ucp_payforlink_module
{
    public $u_action;

    function main($id, $mode)
    {
        global $phpbb_container, $config, $template;

        $this->payforlink_table = $phpbb_container->getParameter('pikaron.payforlink.table.payforlink');

        switch ($mode)
        {
            case 'mybuylinks':
                $this->payforlink_my();
            break;

            case 'whobuymylinks':
                $this->payforlink_who();
            break;

            case 'allmylinks':
                $this->payforlink_all();
            break;

            case 'totallinks':
                $this->payforlink_total();
            break;
        }

        $template->assign_var('PAYFORLINK_VERSION', $config['payforlink_version']);
        $template->assign_var('PAYFORLINK_FOOTER_VIEW', true);
    }

    public function payforlink_my()
    {
        // Check, ¿Cuantos Enlaces he Comprado?
        // INICIO -- Enlaces que he Comprado
        global $db, $user, $template, $request, $config, $phpbb_root_path, $phpbb_container;

        $this->tpl_name = 'payforlink/ucp/ucp_payforlink';
        $this->page_title = $user->lang['UCP_PAYFORLINK_TITLE'];

        $pagination     = $phpbb_container->get('pagination');

        $number         = $config['payforlink_cant']; // Registros por pagina
        $start          = $request->variable('start', 0);
        $sort_key       = $request->variable('sk', 'crono');
        $sort_dir       = $request->variable('sd', 'a');

        $sort_by_text   = array('crono' => $user->lang['POINTS_ORD_CRONO'], 'coste' => $user->lang['POINTS_PRICE_PAY'], 'propi' => $user->lang['POINTS_OWNER']);
        $sort_by_sql    = array('crono' => 'link_id', 'coste' => 'link_cost', 'propi' => 'user_id_prop');

        $limit_days     = array();
        $sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
        gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
        $sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

        // Usuario Conectado
        $id_conec       = (int) $user->data['user_id'];

        // Total de enlaces comprados por el usuario conectado
        $sql = "SELECT COUNT(*) AS total FROM " . $this->payforlink_table ." WHERE user_id_buy = '$id_conec'";
        $result = $db->sql_query($sql);
        $max = (int) $db->sql_fetchfield('total');

        // Make sure $start is set to the last page if it exceeds the amount
        $start = $pagination->validate_start($start, $number, $max);

        // Captura de todos los enlaces comprados por el usuario conectado
        $sql = "SELECT * FROM " . $this->payforlink_table ." WHERE user_id_buy = '$id_conec' ORDER BY " . $sql_sort_order;
        $result = $db->sql_query_limit($sql, $number, $start);

        // Start looping all the enlaces
        $total_points = 0;
        $rows = false;
        while ($row = $db->sql_fetchrow($result))
        {
            // Decodificamos la URL del enlace
            $goback_url     = strrev($row['link_url']);
            $clear_url      = $this->DecodeID($goback_url);
            $datos          = explode('|||',$clear_url);

            $url            = '<a href="'.$datos[0].'" class="button1" title="'.$datos[0].'" target="_blank"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;'.$user->lang['LINK_ADD_LINK_FILE'].'</a>';

            $subject_post   = '<a href="'.$phpbb_root_path.'search.php?keywords='.$row['link_url'].'&sf=msgonly" name="'.$row['link_id'].'" class="button1" title="'.$datos[0].'" target="_blank"><i class="fa fa-search"></i>&nbsp;&nbsp;'.$user->lang['SEARCH'].'</a>';

            // Usuario Propietario
            $sql_array2 = array(
                'SELECT'    => '*',
                'FROM'      => array(
                    USERS_TABLE => 'u',
                ),
                'WHERE'     => 'user_id = ' . (int) $row['user_id_prop'],
            );
            $sql2 = $db->sql_build_query('SELECT', $sql_array2);
            $result12 = $db->sql_query($sql2);
            $owner = $db->sql_fetchrow($result12);
            $db->sql_freeresult($result12);

            $to = is_array($owner) ? get_username_string('full', $owner['user_id'], $owner['username'], $owner['user_colour']) : get_username_string('full', '', '', '');

            $rows = !$rows;
            $total_points = $total_points + $row['link_cost'];

            // Add the items to the template
            $template->assign_block_vars('logs', array(
                'OWNER'         =>  $to,
                'LINK'          =>  $url,
                'SUBJECT'       =>  $subject_post,
                'COST'          =>  $row['link_cost'] . '&nbsp;' . $user->lang['POINTS_POINTS'],
                'S_ROW_COUNT'   =>  $rows,
            ));
        }
        $db->sql_freeresult($result);

        $base_url = append_sid("{$phpbb_root_path}ucp.php?i=-pikaron-payforlink-ucp-ucp_payforlink_module", "mode=mybuylinks&amp;sk=$sort_key&amp;sd=$sort_dir");
        $pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

        // Generate the page template
        $template->assign_vars(array(
            'POINTS_TITLE'      => $user->lang['POINTS_LIST_LINKS_BUY'],
            'POINTS_COLUM1'     => $user->lang['POINTS_OWNER'],
            'POINTS_COLUM2'     => $user->lang['POINTS_LINK'],
            'POINTS_COLUM3'     => $user->lang['POINTS_LINK_LOCATED'],
            'POINTS_COLUM4'     => $user->lang['POINTS_LINK_PAY'],
            'POINTS_TOT'        => $user->lang['POINTS_TOT_PAY'],
            'TRUE_LINK'         => $max > 0 ? true : false,
            'ENLACES_X_PAGINA'  => (($max - $start) < $number ) ? ($max - $start) : $number,
            'TOTAL_POINTS'      => $total_points,
            'S_LOGS_ACTION'     => $base_url,
            'S_SELECT_SORT_KEY' => $s_sort_key,
            'S_SELECT_SORT_DIR' => $s_sort_dir,
            'TOTAL_POSTS'       => $max,
        ));

    }

    public function payforlink_who()
    {
        // Check, ¿Quien ha Comprado mis Enlaces?
        // INICIO -- Enlaces mios que han Comprado
        global $db, $user, $template, $request, $config, $phpbb_root_path, $phpbb_container;

        $this->tpl_name = 'payforlink/ucp/ucp_payforlink';
        $this->page_title = $user->lang['UCP_PAYFORLINK_TITLE'];

        $pagination     = $phpbb_container->get('pagination');

        $number         = $config['payforlink_cant']; // Registros por pagina
        $start          = $request->variable('start', 0);
        $sort_key       = $request->variable('sk', 'crono');
        $sort_dir       = $request->variable('sd', 'a');

        $sort_by_text   = array('crono' => $user->lang['POINTS_ORD_CRONO'], 'coste' => $user->lang['POINTS_PRICE_PAY'], 'propi' => $user->lang['POINTS_OWNER']);
        $sort_by_sql    = array('crono' => 'link_id', 'coste' => 'link_cost', 'propi' => 'user_id_prop');

        $limit_days     = array();
        $sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
        gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
        $sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

        // Usuario Conectado
        $id_conec       = (int) $user->data['user_id'];

        // Total de enlaces comprados por el usuario conectado
        $sql = "SELECT COUNT(*) AS total FROM " . $this->payforlink_table ." WHERE user_id_prop = '$id_conec'";
        $result = $db->sql_query($sql);
        $max = (int) $db->sql_fetchfield('total');

        // Make sure $start is set to the last page if it exceeds the amount
        $start = $pagination->validate_start($start, $number, $max);

        // Captura de todos los enlaces comprados por el usuario conectado
        $sql = "SELECT * FROM " . $this->payforlink_table ." WHERE user_id_prop = '$id_conec' ORDER BY " . $sql_sort_order;
        $result = $db->sql_query_limit($sql, $number, $start);

        // Start looping all the enlaces
        $total_points = 0;
        $rows = false;
        while ($row = $db->sql_fetchrow($result))
        {
            // Decodificamos la URL del enlace
            $goback_url     = strrev($row['link_url']);
            $clear_url      = $this->DecodeID($goback_url);
            $datos          = explode('|||',$clear_url);

            $url            = '<a href="'.$datos[0].'" class="button1" title="'.$datos[0].'" target="_blank"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;'.$user->lang['LINK_ADD_LINK_FILE'].'</a>';

            $subject_post   = '<a href="'.$phpbb_root_path.'search.php?keywords='.$row['link_url'].'&sf=msgonly" name="'.$row['link_id'].'" class="button1" title="'.$datos[0].'" target="_blank"><i class="fa fa-search"></i>&nbsp;&nbsp;'.$user->lang['SEARCH'].'</a>';

            // Usuario Comprador
            $sql_array2 = array(
                'SELECT'    => '*',
                'FROM'      => array(
                    USERS_TABLE => 'u',
                ),
                'WHERE'     => 'user_id = ' . (int) $row['user_id_buy'],
            );
            $sql2 = $db->sql_build_query('SELECT', $sql_array2);
            $result12 = $db->sql_query($sql2);
            $buyer = $db->sql_fetchrow($result12);
            $db->sql_freeresult($result12);

            $to = is_array($buyer) ? get_username_string('full', $buyer['user_id'], $buyer['username'], $buyer['user_colour']) : get_username_string('full', '', '', '');

            $rows = !$rows;
            $total_points = $total_points + $row['link_cost'];

            // Add the items to the template
            $template->assign_block_vars('logs', array(
                'OWNER'         =>  $to,
                'LINK'          =>  $url,
                'SUBJECT'       =>  $subject_post,
                'COST'          =>  $row['link_cost'] . '&nbsp;' . $user->lang['POINTS_POINTS'],
                'S_ROW_COUNT'   =>  $rows,
            ));
        }
        $db->sql_freeresult($result);

        $base_url = append_sid("{$phpbb_root_path}ucp.php?i=-pikaron-payforlink-ucp-ucp_payforlink_module", "mode=whobuymylinks&amp;sk=$sort_key&amp;sd=$sort_dir");
        $pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

        // Generate the page template
        $template->assign_vars(array(
            'POINTS_TITLE'      => $user->lang['POINTS_LIST_LINKS_PAY'],
            'POINTS_COLUM1'     => $user->lang['POINTS_BUYER'],
            'POINTS_COLUM2'     => $user->lang['POINTS_LINK'],
            'POINTS_COLUM3'     => $user->lang['POINTS_LINK_LOCATED'],
            'POINTS_COLUM4'     => $user->lang['POINTS_LINK_BUY'],
            'POINTS_TOT'        => $user->lang['POINTS_TOT_BUY'],
            'TRUE_LINK'         => $max > 0 ? true : false,
            'ENLACES_X_PAGINA'  => (($max - $start) < $number ) ? ($max - $start) : $number,
            'TOTAL_POINTS'      => $total_points,
            'S_LOGS_ACTION'     => $base_url,
            'S_SELECT_SORT_KEY' => $s_sort_key,
            'S_SELECT_SORT_DIR' => $s_sort_dir,
            'TOTAL_POSTS'       => $max,
        ));
    }

    public function payforlink_all()
    {
        // Check, Todos Mis Enlaces
        // INICIO -- Todos Mis Enlaces
        global $db, $user, $template, $request, $config, $phpbb_root_path, $phpbb_container;

        $this->tpl_name = 'payforlink/ucp/ucp_payforlink';
        $this->page_title = $user->lang['UCP_PAYFORLINK_TITLE'];

        $pagination     = $phpbb_container->get('pagination');

        $number         = $config['payforlink_cant']; // Registros por pagina
        $start          = $request->variable('start', 0);
        $sort_key       = $request->variable('sk', 'crono');
        $sort_dir       = $request->variable('sd', 'a');

        $sort_by_text   = array('crono' => $user->lang['POINTS_ORD_CRONO'], 'creador' => $user->lang['POINTS_LINK_CREATOR'], 'asunto' => $user->lang['POINTS_ORD_SUBJECT']);
        $sort_by_sql    = array('crono' => 'post_time', 'creador' => 'poster_id', 'asunto' => 'post_subject');

        $limit_days     = array();
        $sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
        gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
        $sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

        // Usuario Conectado
        $id_conec       = (int) $user->data['user_id'];

        // Busca en TODOS los post cualquier URL codificada
        $buscame = '[payforlink]';
        $sql = "SELECT post_id, poster_id, post_subject, post_text, bbcode_uid FROM " . POSTS_TABLE ." WHERE post_text LIKE '%$buscame%' ORDER BY " . $sql_sort_order;
        $result = $db->sql_query($sql);

        $total_points = $max = $contados = 0;
        $rows = false;
        // Coge uno a uno los valores de los pots hallados
        while ($row = $db->sql_fetchrow($result))
        {
            $to = $subject_post = $url_tot = '';

            $textodelpost = $row['post_text'];
            decode_message($textodelpost, $row['bbcode_uid']);
            $links = '';
            $links = $this->Return_Substrings($textodelpost, '[payforlink]', '[/payforlink]');

            // Todas las URL de enlaces codificadas dentro de un mensaje
            for($i=0; $i<count($links);$i++)
            {
                // Decodificamos la URL del enlace
                $goback_url     = strrev($links[$i]);
                $clear_url      = $this->DecodeID($goback_url);
                $datos          = explode('|||',$clear_url);
                $url            = $datos[0];
                $precio         = $datos[1];
                $id_prop        = $datos[2];

                // Verificamos que el enlace es propiedad del usuario conectado
                if ($id_conec == $id_prop)
                {
                    $max++;
                    // Solo imprime el rango comprendido desde start con un maximo de number
                    if (($max > $start) and ($number > $contados))
                    {
                        $contados++;
                        // Usuario Creador del Hilo
                        $sql_array2 = array(
                            'SELECT'    => '*',
                            'FROM'      => array(
                                USERS_TABLE => 'u',
                            ),
                            'WHERE'     => 'user_id = ' . (int) $row['poster_id'],
                        );
                        $sql2 = $db->sql_build_query('SELECT', $sql_array2);
                        $result12 = $db->sql_query($sql2);
                        $buyer = $db->sql_fetchrow($result12);
                        $db->sql_freeresult($result12);

                        $url_tot = '<a href="'.$url.'" class="button1" title="'.$url.'" target="_blank"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;'.$user->lang['LINK_ADD_LINK_FILE'].'</a>';
                        if ($row['post_subject'] == '')
                        {
                            $subject_post = '<a href="'.$phpbb_root_path.'viewtopic.php?p='.$row['post_id'].'#p'.$row['post_id'].'">'.$user->lang['POINTS_LINK_NO_SUBJECT'].'</a><br>';
                        } else {
                            $subject_post = '<a href="'.$phpbb_root_path.'viewtopic.php?p='.$row['post_id'].'#p'.$row['post_id'].'">'.$row['post_subject'].'</a><br>';
                        }
                        $to = is_array($buyer) ? get_username_string('full', $buyer['user_id'], $buyer['username'], $buyer['user_colour']) : get_username_string('full', '', '', '');
                        $rows = !$rows;
                        $total_points = $total_points + $precio;

                        // Add the items to the template
                        $template->assign_block_vars('logs', array(
                            'OWNER'         =>  $subject_post,
                            'LINK'          =>  $url_tot,
                            'SUBJECT'       =>  $to,
                            'COST'          =>  $precio . '&nbsp;' . $user->lang['POINTS_POINTS'],
                            'S_ROW_COUNT'   =>  $rows,
                        ));

                    }
                }
            }
        }
        $db->sql_freeresult($result);

        // Make sure $start is set to the last page if it exceeds the amount
        $start = $pagination->validate_start($start, $number, $max);
        $base_url = append_sid("{$phpbb_root_path}ucp.php?i=-pikaron-payforlink-ucp-ucp_payforlink_module", "mode=allmylinks&amp;sk=$sort_key&amp;sd=$sort_dir");
        $pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

        // Generate the page template
        $template->assign_vars(array(
            'POINTS_TITLE'      => $user->lang['POINTS_LIST_LINKS_MEE'],
            'POINTS_COLUM1'     => $user->lang['POINTS_LINK_LOCATED'],
            'POINTS_COLUM2'     => $user->lang['POINTS_LINK'],
            'POINTS_COLUM3'     => $user->lang['POINTS_LINK_CREATOR'],
            'POINTS_COLUM4'     => $user->lang['POINTS_POINTS'],
            'POINTS_TOT'        => $user->lang['POINTS_TOT_MEE'],
            'TRUE_LINK'         => $max > 0 ? true : false,
            'ENLACES_X_PAGINA'  => (($max - $start) < $number ) ? ($max - $start) : $number,
            'TOTAL_POINTS'      => $total_points,
            'S_LOGS_ACTION'     => $base_url,
            'S_SELECT_SORT_KEY' => $s_sort_key,
            'S_SELECT_SORT_DIR' => $s_sort_dir,
            'TOTAL_POSTS'       => $max,
        ));
    }

    public function payforlink_total()
    {
        // Check, Todos los Enlaces del Foro
        // INICIO -- Todos los Enlaces del Foro
        global $db, $user, $template, $request, $config, $phpbb_root_path, $phpbb_container;

        $this->tpl_name = 'payforlink/ucp/ucp_payforlink';
        $this->page_title = $user->lang['UCP_PAYFORLINK_TITLE'];

        $pagination     = $phpbb_container->get('pagination');

        $number         = $config['payforlink_cant']; // Registros por pagina
        $start          = $request->variable('start', 0);
        $sort_key       = $request->variable('sk', 'crono');
        $sort_dir       = $request->variable('sd', 'a');

        $sort_by_text   = array('crono' => $user->lang['POINTS_ORD_CRONO'], 'creador' => $user->lang['POINTS_LINK_CREATOR'], 'asunto' => $user->lang['POINTS_ORD_SUBJECT']);
        $sort_by_sql    = array('crono' => 'post_time', 'creador' => 'poster_id', 'asunto' => 'post_subject');

        $limit_days     = array();
        $sort_days = $s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
        gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);
        $sql_sort_order = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

        // Total de TODOS los post con cualquier URL codificada
        $buscame = '[payforlink]';
        $sql = "SELECT COUNT(*) AS total FROM " . POSTS_TABLE ." WHERE post_text LIKE '%$buscame%'";
        $result = $db->sql_query($sql);
        $max = (int) $db->sql_fetchfield('total');
        $db->sql_freeresult($result);

        // Make sure $start is set to the last page if it exceeds the amount
        $start = $pagination->validate_start($start, $number, $max);

        // Busca en TODOS los post cualquier URL codificada
        $sql = "SELECT post_id, poster_id, post_subject, post_text, bbcode_uid FROM " . POSTS_TABLE ." WHERE post_text LIKE '%$buscame%' ORDER BY " . $sql_sort_order;
        $result = $db->sql_query_limit($sql, $number, $start);

        $total_points = 0;
        $rows = false;
        while ($row = $db->sql_fetchrow($result))
        {
            $who = $to = $subject_post = '';

            $textodelpost = $row['post_text'];
            decode_message($textodelpost, $row['bbcode_uid']);
            $links = '';
            $links = $this->Return_Substrings($textodelpost, '[payforlink]', '[/payforlink]');

            // Todas las URL de enlaces codificadas dentro de un mensaje
            for($i=0; $i<count($links);$i++)
            {
                // Decodificamos la URL del enlace
                $goback_url     = strrev($links[$i]);
                $clear_url      = $this->DecodeID($goback_url);
                $datos          = explode('|||',$clear_url);
                $precio         = $datos[1];
                $id_prop        = $datos[2];

                // Usuario Propietario del enlace
                $sql_array7 = array(
                    'SELECT'    => '*',
                    'FROM'      => array(
                        USERS_TABLE => 'u',
                    ),
                    'WHERE'     => 'user_id = ' . $id_prop,
                );
                $sql7 = $db->sql_build_query('SELECT', $sql_array7);
                $result7 = $db->sql_query($sql7);
                $owner = $db->sql_fetchrow($result7);
                $db->sql_freeresult($result7);

                // Usuario Creador del Hilo
                $sql_array2 = array(
                    'SELECT'    => '*',
                    'FROM'      => array(
                        USERS_TABLE => 'u',
                    ),
                    'WHERE'     => 'user_id = ' . (int) $row['poster_id'],
                );
                $sql2 = $db->sql_build_query('SELECT', $sql_array2);
                $result12 = $db->sql_query($sql2);
                $buyer = $db->sql_fetchrow($result12);
                $db->sql_freeresult($result12);

                if ($row['post_subject'] == '')
                {
                    $subject_post = '<a href="'.$phpbb_root_path.'viewtopic.php?p='.$row['post_id'].'#p'.$row['post_id'].'">'.$user->lang['POINTS_LINK_NO_SUBJECT'].'</a><br>';
                } else {
                    $subject_post = '<a href="'.$phpbb_root_path.'viewtopic.php?p='.$row['post_id'].'#p'.$row['post_id'].'">'.$row['post_subject'].'</a><br>';
                }
                $to = is_array($buyer) ? get_username_string('full', $buyer['user_id'], $buyer['username'], $buyer['user_colour']) : get_username_string('full', '', '', '');
                $who = is_array($owner) ? get_username_string('full', $owner['user_id'], $owner['username'], $owner['user_colour']) : get_username_string('full', '', '', '');
                $rows = !$rows;
                $total_points = $total_points + $precio;

                // Add the items to the template
                $template->assign_block_vars('logs', array(
                    'OWNER'         =>  $subject_post,
                    'LINK'          =>  $who,
                    'SUBJECT'       =>  $to,
                    'COST'          =>  $precio . '&nbsp;' . $user->lang['POINTS_POINTS'],
                    'S_ROW_COUNT'   =>  $rows,
                ));
            }
        }
        $db->sql_freeresult($result);

        $base_url = append_sid("{$phpbb_root_path}ucp.php?i=-pikaron-payforlink-ucp-ucp_payforlink_module", "mode=totallinks&amp;sk=$sort_key&amp;sd=$sort_dir");
        $pagination->generate_template_pagination($base_url, 'pagination', 'start', $max, $number, $start);

        // Generate the page template
        $template->assign_vars(array(
            'POINTS_TITLE'      => $user->lang['POINTS_LIST_LINKS_ALL'],
            'POINTS_COLUM2'     => $user->lang['POINTS_OWNER'],
            'POINTS_COLUM1'     => $user->lang['POINTS_LINK_LOCATED'],
            'POINTS_COLUM3'     => $user->lang['POINTS_LINK_CREATOR'],
            'POINTS_COLUM4'     => $user->lang['POINTS_POINTS'],
            'POINTS_TOT'        => $user->lang['POINTS_TOT_MEE'],
            'TRUE_LINK'         => $max > 0 ? true : false,
            'ENLACES_X_PAGINA'  => (($max - $start) < $number ) ? ($max - $start) : $number,
            'TOTAL_POINTS'      => $total_points,
            'S_LOGS_ACTION'     => $base_url,
            'S_SELECT_SORT_KEY' => $s_sort_key,
            'S_SELECT_SORT_DIR' => $s_sort_dir,
            'TOTAL_POSTS'       => $max,
        ));
    }


    function DecodeID($string)
    {
        $decode = str_replace("cDFjNHIw","",$string);
        $decode = str_replace("RWwzVjR0","",$decode);
        $decoded = base64_decode(str_pad(strtr($decode, '-_', '+/'), strlen($decode) % 4, '=', STR_PAD_RIGHT));
        return $decoded;
    }

    function Return_Substrings($text, $sopener, $scloser)
    {
        $result = array();

        $noresult = substr_count($text, $sopener);
        $ncresult = substr_count($text, $scloser);

        if ($noresult < $ncresult)
        {
            $nresult = $noresult;
        } else {
            $nresult = $ncresult;
        }

        unset($noresult);
        unset($ncresult);

        for ($i=0;$i<$nresult;$i++)
            {
                $pos = strpos($text, $sopener) + strlen($sopener);
                $text = substr($text, $pos, strlen($text));
                $pos = strpos($text, $scloser);
                $result[] = substr($text, 0, $pos);
                $text = substr($text, $pos + strlen($scloser), strlen($text));
            }
        return $result;
    }
}
?>