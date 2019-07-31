<?php


namespace App\Repository;


use App\Models\Qr;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrRepository extends AbstractRepository
{


    public function __construct(Qr $qr)
    {
        $this->model = $qr;
        $this->rules = [
            'title' => 'string|required',
            'description' => 'string',
            'status' => 'int|required'
        ];
    }

    public function fetch($id, $params = [])
    {


        return $this->model->find($id);
    }

    public function fetchAll($params = [])
    {

        return $params;

    }

    public function store($data)
    {

        $imgName = 'img'.random_int(0, 100000);

        $content = $this->buildLink('qrs', [
            'imgName' => $imgName,
            'embed' => 'asset'
        ]);

        // public link to access stored qr-code image
        $imgLink = $this->buildLink('storage', [
            'qrs' => $imgName
        ]);

        // internal link to stored qr-code image
        $imgPath = $this->makeQrFilePath($imgName);

        $isCreated = $this->makeQrFile($content, $imgPath);

        if ($isCreated === false) {
            abort(422, 'unable to create Qr-Code');
        }

        $data = array_merge($data, compact('imgPath', 'imgLink', 'imgName'));

        return $this->model->newQuery()->create($data);

    }


    private function makeQrFilePath(string $fileName): string
    {
        $fileExt = '.png';
        $file = $fileName.$fileExt;
        return storage_path('app/uploads/'.$file);
    }

    private function makeQrFile($content, string $filePath): bool
    {

        return (bool) QrCode::size(500)->format('png')->generate($content, $filePath);

    }

    public function update($data, $id = null)
    {

    }

    public function delete($id)
    {

    }

}
