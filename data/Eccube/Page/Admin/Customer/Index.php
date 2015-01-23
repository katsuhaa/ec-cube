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

namespace Eccube\Page\Admin\Customer;

use Eccube\Page\Admin\AbstractAdminPage;
use Eccube\Common\CustomerList;
use Eccube\Common\Date;
use Eccube\Common\FormParam;
use Eccube\Common\Response;
use Eccube\Common\DB\MasterData;
use Eccube\Common\Helper\CsvHelper;
use Eccube\Common\Helper\CustomerHelper;
use Eccube\Common\Helper\DbHelper;
use Eccube\Common\Helper\MailHelper;
use Eccube\Common\Util\Utils;

/**
 * 会員管理 のページクラス.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 */
class Index extends AbstractAdminPage
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->tpl_mainpage = 'customer/index.tpl';
        $this->tpl_mainno = 'customer';
        $this->tpl_subno = 'index';
        $this->tpl_pager = 'pager.tpl';
        $this->tpl_maintitle = '会員管理';
        $this->tpl_subtitle = '会員マスター';

        $masterData = new MasterData();
        $this->arrPref = $masterData->getMasterData('mtb_pref');
        $this->arrJob = $masterData->getMasterData('mtb_job');
        $this->arrJob['不明'] = '不明';
        $this->arrSex = $masterData->getMasterData('mtb_sex');
        $this->arrPageMax = $masterData->getMasterData('mtb_page_max');
        $this->arrStatus = $masterData->getMasterData('mtb_customer_status');
        $this->arrMagazineType = $masterData->getMasterData('mtb_magazine_type');

        // 日付プルダウン設定
        $objDate = new Date();
        // 登録・更新日検索用
        $objDate->setStartYear(RELEASE_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrRegistYear = $objDate->getYear();
        // 生年月日検索用
        $objDate->setStartYear(BIRTH_YEAR);
        $objDate->setEndYear(DATE('Y'));
        $this->arrBirthYear = $objDate->getYear();
        // 月日の設定
        $this->arrMonth = $objDate->getMonth();
        $this->arrDay = $objDate->getDay();

        // カテゴリ一覧設定
        $objDb = new DbHelper();
        $this->arrCatList = $objDb->getCategoryList();

        $this->httpCacheControl('nocache');
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
        // パラメーター管理クラス
        $objFormParam = new FormParam();
        // パラメーター設定
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        // パラメーター読み込み
        $this->arrForm = $objFormParam->getFormParamList();
        // 検索ワードの引き継ぎ
        $this->arrHidden = $objFormParam->getSearchArray();

        // 入力パラメーターチェック
        $this->arrErr = $this->lfCheckError($objFormParam);
        if (!Utils::isBlank($this->arrErr)) {
            return;
        }

        // モードによる処理切り替え
        switch ($this->getMode()) {
            case 'delete':
                $this->is_delete = $this->lfDoDeleteCustomer($objFormParam->getValue('edit_customer_id'));
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'resend_mail':
                $this->is_resendmail = $this->lfDoResendMail($objFormParam->getValue('edit_customer_id'));
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'search':
                list($this->tpl_linemax, $this->arrData, $this->objNavi) = $this->lfDoSearch($objFormParam->getHashArray());
                $this->arrPagenavi = $this->objNavi->arrPagenavi;
                break;
            case 'csv':

                $this->lfDoCSV($objFormParam->getHashArray());
                Response::actionExit();
                break;
            default:
                break;
        }

    }

    /**
     * パラメーター情報の初期化
     *
     * @param  FormParam $objFormParam フォームパラメータークラス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        CustomerHelper::sfSetSearchParam($objFormParam);
        $objFormParam->addParam('編集対象会員ID', 'edit_customer_id', INT_LEN, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'));
    }

    /**
     * エラーチェック
     *
     * @param  FormParam $objFormParam フォームパラメータークラス
     * @return array エラー配列
     */
    public function lfCheckError(&$objFormParam)
    {
        return CustomerHelper::sfCheckErrorSearchParam($objFormParam);
    }

    /**
     * 会員を削除する処理
     *
     * @param  integer $customer_id 会員ID
     * @return boolean true:成功 false:失敗
     */
    public function lfDoDeleteCustomer($customer_id)
    {
        return CustomerHelper::delete($customer_id);
    }

    /**
     * 会員に登録メールを再送する処理
     *
     * @param  integer $customer_id 会員ID
     * @return boolean true:成功 false:失敗
     */
    public function lfDoResendMail($customer_id)
    {
        $arrData = CustomerHelper::sfGetCustomerDataFromId($customer_id);
        if (Utils::isBlank($arrData) or $arrData['del_flg'] == 1) {
            //対象となるデータが見つからない、または削除済み
            return false;
        }
        //仮登録メール再送
        $resend_flg = true; 
        // 登録メール再送
        $objHelperMail = new MailHelper();
        $objHelperMail->setPage($this);
        $objHelperMail->sfSendRegistMail($arrData['secret_key'], $customer_id, null, $resend_flg);
        return true;
    }

    /**
     * 会員一覧を検索する処理
     *
     * @param  array  $arrParam 検索パラメーター連想配列
     * @return array( integer 全体件数, mixed 会員データ一覧配列, mixed PageNaviオブジェクト)
     */
    public function lfDoSearch($arrParam)
    {
        return CustomerHelper::sfGetSearchData($arrParam);
    }

    /**
     * 会員一覧CSVを検索してダウンロードする処理
     *
     * @param  array   $arrParam 検索パラメーター連想配列
     * @return boolean|string true:成功 false:失敗
     */
    public function lfDoCSV($arrParam)
    {
        $objSelect = new CustomerList($arrParam, 'customer');
        $objCSV = new CsvHelper();

        $order = 'update_date DESC, customer_id DESC';

        list($where, $arrVal) = $objSelect->getWhere();

        return $objCSV->sfDownloadCsv('2', $where, $arrVal, $order, true);
    }
}