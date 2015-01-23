<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Page\Admin\Mail;

use Eccube\Page\Admin\AbstractAdminPage;
use Eccube\Common\Query;
use Eccube\Common\DB\MasterData;
use Eccube\Common\Helper\MailHelper;
use Eccube\Common\Util\Utils;

/**
 * メールテンプレート設定 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 */
class Template extends AbstractAdminPage
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'mail/template.tpl';
        $this->tpl_mainno = 'mail';
        $this->tpl_subno = 'template';
        $this->tpl_maintitle = 'メルマガ管理';
        $this->tpl_subtitle = 'テンプレート設定';

        $masterData = new MasterData();
        $this->arrMagazineType = $masterData->getMasterData('mtb_magazine_type');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $objMailHelper = new MailHelper();

        switch ($this->getMode()) {
            case 'delete':
                if (Utils::sfIsInt($_GET['id'])===true) {
                    $this->lfDeleteMailTemplate($_GET['id']);

                    $this->objDisplay->reload(null, true);
                }
                break;
            default:
                break;
        }
        $this->arrTemplates = $objMailHelper->sfGetMailmagaTemplate();
    }

    /**
     * メールテンプレートの削除
     * @param integer 削除したいテンプレートのID
     * @return void
     */
    public function lfDeleteMailTemplate($template_id)
    {
        $objQuery = Query::getSingletonInstance();
        $objQuery->update('dtb_mailmaga_template',
                          array('del_flg' =>1),
                          'template_id = ?',
                          array($template_id));
    }
}