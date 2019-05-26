<?php
/**
 * PayForLink extension for the phpBB >=3.2.0 Forum Software package.
 * @copyright (c) 2017 Picaron
 * @license GNU General Public License, version 2 (GPL-2.0)
 */

namespace Picaron\PayForLink\controller;

class main
{
    /** @var \Picaron\PayForLink\core\payforlink_preview_boton */
    protected $payforlink_preview_boton;

    /** @var \Picaron\PayForLink\core\payforlink_download_boton */
    protected $payforlink_download_boton;

    /** @var \Picaron\PayForLink\core\payforlink_input_link */
    protected $payforlink_input_link;

    /** @var \Picaron\PayForLink\core\payforlink_make_link */
    protected $payforlink_make_link;

    /** @var \phpbb\user */
    protected $user;

    /** @var \phpbb\request\request */
    protected $request;


    /**
    * Constructor
    *
    * @var \Picaron\PayForLink\core\payforlink_preview_boton
    * @var \Picaron\PayForLink\core\payforlink_download_boton
    * @param \phpbb\user                                        $user
    * @param \phpbb\request\request                             $request
    *
    */
    public function __construct(
        \Picaron\PayForLink\core\payforlink_preview_boton $payforlink_preview_boton,
        \Picaron\PayForLink\core\payforlink_download_boton $payforlink_download_boton,
        \Picaron\PayForLink\core\payforlink_input_link $payforlink_input_link,
        \Picaron\PayForLink\core\payforlink_make_link $payforlink_make_link,
        \phpbb\user $user,
        \phpbb\request\request $request
    )
    {
        $this->payforlink_preview_boton     = $payforlink_preview_boton;
        $this->payforlink_download_boton    = $payforlink_download_boton;
        $this->payforlink_input_link        = $payforlink_input_link;
        $this->payforlink_make_link         = $payforlink_make_link;
        $this->user                         = $user;
        $this->request                      = $request;
    }

    public function handle_payforlink()
    {
        $mode = $this->request->variable('mode', '');
        $urlcodec = $this->request->variable('urlcodec', '');

        if (!$mode)
        {
            trigger_error('LINK_ADD_ERROR');
        }

        switch ($mode)
        {
            case 'download':
                $this->payforlink_download_boton->main($urlcodec);
            break;

            case 'preview':
                $this->payforlink_preview_boton->main($urlcodec);
            break;

            case 'input':
                $this->payforlink_input_link->main();
            break;

            case 'make':
                $this->payforlink_make_link->main();
            break;
        }
    }
}
?>