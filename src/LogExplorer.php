<?php

namespace WCast\Log;

use Illuminate\Support\Facades\Storage;

class LogExplorer
{

    public function getlogs()
    {
        $this->getFolders();
    }

    public function getFolders()
    {
        $diretorio = storage_path().'/logs';
        $scandir =  scandir($diretorio);
        $scandir = array_filter($scandir, function($pasta){
            return !preg_match('/^\./', $pasta);
        });
        $pastas = [];
        foreach($scandir as $pasta){
            if(is_dir($diretorio . DIRECTORY_SEPARATOR . $pasta)){
                $pastas[] = [
                   'nome' => $pasta,
                   'diretorio' => encrypt($diretorio . DIRECTORY_SEPARATOR . $pasta)
                ];
            }
        }
        return $pastas;
    }

    public function getFiles($data = null)
    {

        $scandir =  scandir(decrypt($diretorio));
        $scandir = array_filter($scandir, function($arquivo){
            return !preg_match('/^\./', $arquivo);
        });
        $arquivos = [];
        foreach($scandir as $arquivo){
            if(is_file(decrypt($diretorio).DIRECTORY_SEPARATOR.$arquivo)){
                $arquivos[] = [
                    'nome' => $arquivo,
                    'arquivo' => $diretorio,
                    'tamanho' => $this->converterBytes(filesize(decrypt($diretorio).DIRECTORY_SEPARATOR.$arquivo),2),
                    'criado' => date('d/m/Y H:i:s',fileatime(decrypt($diretorio).DIRECTORY_SEPARATOR.$arquivo))
                ];
            }
        }
        return $arquivos;
    }

    private function converterBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'kB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
}
