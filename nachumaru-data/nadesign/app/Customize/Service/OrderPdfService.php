<?php

namespace Customize\Service;

class OrderPdfService extends \Eccube\Service\OrderPdfService
{

    /**
     * PDFに備考を設定数.
     *
     * @param array $formData
     */
    protected function renderEtcData(array $formData)
    {
        // フォント情報のバックアップ
        $this->backupFont();

        $this->Cell(0, 10, '', 0, 1, 'C', 0, '');

        $this->SetFont(self::FONT_GOTHIC, '', 12);
        $this->MultiCell(0, 6, '２０歳未満の方の飲酒は法律で禁じられています。', 'T', 2, 'L', 0, '');
        $this->Ln();
        $this->MultiCell(0, 6, '２０歳未満の方への酒類販売は致しません。', '', 2, 'L', 0, '');
        
        $this->SetFont(self::FONT_SJIS, '', 8);

        // フォント情報の復元
        $this->restoreFont();

        return parent::renderEtcData($formData);
    }
}
