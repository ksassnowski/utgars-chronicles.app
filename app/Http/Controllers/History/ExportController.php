<?php

declare(strict_types=1);

namespace App\Http\Controllers\History;

use App\Export\HistoryExporter;
use App\History;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use League\Csv\Writer;
use SplTempFileObject;

final class ExportController extends Controller
{
    private HistoryExporter $exporter;

    public function __construct(HistoryExporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function __invoke(Request $request, History $history)
    {
        if ($history->periods()->count() === 0) {
            return redirect()->back()->with('error', __('Cannot export an empty game'));
        }

        if (!\ini_get('auto_detect_line_endings')) {
            \ini_set('auto_detect_line_endings', '1');
        }

        $writer = Writer::createFromFileObject(new SplTempFileObject());
        $writer->insertAll($this->exporter->export($history));

        return response((string) $writer, 200, [
            'Content-Transfer-Encoding' => 'binary',
            'Content-Type' => 'text/csv',
            'Content-Disposition' => \sprintf('attachment; filename="%s.csv"', $history->name),
        ]);
    }
}
