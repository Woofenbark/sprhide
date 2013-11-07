<?php

class Sprhide {

	private $row_heights = array();
	private $new_row_order = array();
	private $row_offsets = array();
	private $id;
	private $new_filename;

    public function __construct($id, $filename, $num_rows=20) {
        $image = $this->open_image($filename);

        if ($image === false) {
           die ('Unable to open image');
        }

        $this->new_filename = './sprhide_'.$id.'.jpg';
        $this->scramble($filename, $image, $this->new_filename, $num_rows);

        $this->id = $id;

        for ($row = 0; $row < $num_rows; $row++) {
   			$this->row_offsets[$row] = $this->new_row_order[$row] * $this->row_heights[0];
		}

        echo '<img class="sprhide" id="'.$this->id.'" src="'.$this->new_filename.'"/>';

        return;
    }

    public function sprhide_image() {
		$num_rows = count($this->row_heights);

      	echo 'var height = $("img.sprhide").height();';
      	echo 'var width = $("img.sprhide").width();';
      	echo '$("img.sprhide").replaceWith("<div class=\"sprhide\" height=\"+ height +\"px width=\"+ width +\"px></div>");';

       	for ($row = 0; $row < $num_rows; $row++) {
      		echo '$(".sprhide").append("<div id=\"sprhide'.$this->id.'-'.$row.'\"></div>");';
      		echo '$("#sprhide'.$this->id.'-'.$row.'").css("height", '.$this->row_heights[$row].');';
      		echo '$("#sprhide'.$this->id.'-'.$row.'").css("width", width);';
     		$backgroundcss = 'url('.$this->new_filename.') 0px -'.$this->row_offsets[$row].'px no-repeat';
      		echo '$("#sprhide'.$this->id.'-'.$row.'").css("background", "'.$backgroundcss.'");';
      	}
    }

    private function scramble($filename, $orig_image, $new_filename, $num_rows) {
        // Create new canvas, same size as original image
        $size = getimagesize($filename);
        $width = $size[0];
        $height = $size[1];

        // Make sure we're not trying to create more rows than pixels exist
        if ($num_rows > $height) {
        	$num_rows = $height;
        }

        $row_order = array();

        // Round up nominal row height so last row isn't larger than others
        $row_height = ceil($height/$num_rows);
        // Make array of row heights and populate temp row_order array
        for ($row = 0; $row < $num_rows-1; $row++) {
        	$this->row_heights[$row] = $row_height;
        	$row_order[$row] = $row;
        }
        // Last row height is remainder of height value
        $this->row_heights[$num_rows-1] = $height - ($row_height * ($num_rows -1));
        $row_order[$num_rows-1] = $num_rows-1;

        // Mix up the rows
        $this->new_row_order = $this->shuffle($row_order);

        // Copy original to new canvas
        $canvas = imagecreatetruecolor($width, $height);

        $orig_y_offset = 0;
        for ($row = 0; $row < $num_rows; $row++) {
        	$copy_height = $this->row_heights[$row];
        	$new_y_offset = 0;
        	for ($row_c = 0; $row_c < $this->new_row_order[$row]; $row_c++) {
        		// Add up the row heights to get to where this row should go
        		$new_y_offset += $this->row_heights[$row_c];
        	}

            imagecopyresized($canvas, $orig_image, 0, $new_y_offset, 0, $orig_y_offset, $width, $copy_height, $width, $copy_height);
            $orig_y_offset += $copy_height;

        }

        // Save image
        imagejpeg($canvas, $new_filename);

        return;
    }

    private function shuffle ($row_order) {
    	$n = count($row_order) - 1;
    	while ($n > 1) {
    		$k = rand(0, $n);
    		$n--;
    		$temp = $row_order[$n];
    		$row_order[$n] = $row_order[$k];
    		$row_order[$k] = $temp;
    	}

    	return $row_order;
    }

    private function open_image ($file) {
        # JPEG:
        $im = imagecreatefromjpeg($file);
        if ($im !== false) { return $im; }

        # GIF:
        $im = @imagecreatefromgif($file);
        if ($im !== false) { return $im; }

        # PNG:
        $im = @imagecreatefrompng($file);
        if ($im !== false) { return $im; }

        # GD File:
        $im = @imagecreatefromgd($file);
        if ($im !== false) { return $im; }

        # GD2 File:
        $im = @imagecreatefromgd2($file);
        if ($im !== false) { return $im; }

        # WBMP:
        $im = @imagecreatefromwbmp($file);
        if ($im !== false) { return $im; }

        # XBM:
        $im = @imagecreatefromxbm($file);
        if ($im !== false) { return $im; }

        # XPM:
        $im = @imagecreatefromxpm($file);
        if ($im !== false) { return $im; }

        # Try and load from string:
        $im = @imagecreatefromstring(file_get_contents($file));
        if ($im !== false) { return $im; }

        return false;
    }
}

?>
