<?php
namespace App\Http\Controllers;

use App\Http\Requests\PdfRequest;
use App\Watermark;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Spatie\PdfToImage\Pdf;

class WatermarkController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $files = Watermark::orderBy('id', 'DESC')->paginate(10);
        return view('watermark.index', compact('files'));
    }

    /**
     * @param PdfRequest $request
     * @throws \Spatie\PdfToImage\Exceptions\PdfDoesNotExist
     */
    public function store(PdfRequest $request) {

        $id = uniqid();
        try {
            $pdf = new Pdf($request->file('file')->getRealPath());

            $path = storage_path("app/images/{$id}/input");
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $pdf->saveAllPagesAsImages($path);

            $out = storage_path("app/images/{$id}/output");

            if(!File::isDirectory($out)){
                File::makeDirectory($out, 0777, true, true);
            }

            for($i = 1; $i <= $pdf->getNumberOfPages(); $i++) {
                $watermark = Image::make($path."/{$i}.jpg")
                    ->insert(public_path('images/logo.png'), 'center')
                    ->insert(public_path('images/slogan.png'), 'top-center')
                    ->insert(public_path('images/slogan.png'), 'bottom-center')
                    ->save($out."/{$i}.jpg");
            }

            $zip_file = "{$id}.zip";
            $zip = new \ZipArchive();
            $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

            $path = storage_path("app/images/{$id}/output");
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

            foreach ($files as $name => $file)
            {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();

                    // extracting filename with substr/strlen
                    $relativePath = substr($filePath, strlen($path) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            File::deleteDirectory(storage_path("app/images/{$id}"));

            Watermark::create(['name' => $zip_file, 'origin' => $request->file('file')->getClientOriginalName()]);

            return back()->with('file', $zip_file);
        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }
    }
}
