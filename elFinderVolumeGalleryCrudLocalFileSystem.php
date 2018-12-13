<?php

class elFinderVolumeGalleryCrudLocalFileSystem extends \elFinderVolumeLocalFileSystem
{
    /**
     * Return thumbnail file name for required file
     *
     * @param  array  $stat  file stat
     * @return string
     * @author Dmitry (dio) Levashov
     **/
    protected function tmbname($stat) {
        $dir = $this->relpathCE($this->decode($stat['phash']));
		if (! is_dir($this->tmbPath.DIRECTORY_SEPARATOR.$dir)) {
			$dirs = explode(DIRECTORY_SEPARATOR, $dir);
			$target = $this->tmbPath;
			foreach($dirs as $_dir) {
				if (! is_dir($target . DIRECTORY_SEPARATOR . $_dir)) {
					mkdir($target . DIRECTORY_SEPARATOR . $_dir);
				}
				$target = $target . DIRECTORY_SEPARATOR . $_dir;
			}
		}
        return $dir . DIRECTORY_SEPARATOR . $stat['name'] . '?w=80&h=80&fit=fill';
    }
}
