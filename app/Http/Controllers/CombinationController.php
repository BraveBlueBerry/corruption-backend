<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class CombinationController extends Controller
{
	public static function calculate($maxCorruption) {
		$items = Item::all();
		$categories = [];
		foreach($items as $item) {
			if(!array_key_exists($item->slot, $categories))
				$categories[$item->slot] = [];
			$categories[$item->slot][] = $item;
		}
		$options = self::recursivePermutations($categories);
		$best_option = self::findHighestGoodValue($maxCorruption, $options);
		return $best_option;
	}

	public static function calculateForCharacter($maxCorruption, $character) {
		$items = Item::where('character', '=', $character)->get();
		$categories = [];
		foreach($items as $item) {
			if(!array_key_exists($item->slot, $categories))
				$categories[$item->slot] = [];
			$categories[$item->slot][] = $item;
		}
		$options = self::recursivePermutations($categories);
		$best_option = self::findHighestGoodValue($maxCorruption, $options);
		return $best_option;
	}

	public static function recursivePermutations($categories, $options = []) {
	  $new_options = [];
	  foreach ($categories as $key => $category) { // Not foreach, take first
	    unset($categories[$key]);
	    foreach($category as $item) {
	      if (empty($options)) {
	        $new_options[] = [$item];
	      } else {
	        foreach($options as $option) {
	          $option[] = $item;
	          $new_options[] = $option;
	        }
	      }
	    }
	    break;
	  }
	  if(empty($categories)) {
	    return $new_options;
	  }
	  return self::recursivePermutations($categories, $new_options);
	}

	public static function findHighestGoodValue($max_bad_value, $combinations) {
	  $highest_id = null;
	  $highest_value = null;
	  $highest_bad_value = null;
	  foreach($combinations as $id => $combi) {
	    $set_good_value = 0;
	    $set_bad_value = 0;
	    foreach($combi as $item) {
	      $set_good_value += $item->hps;
	      $set_bad_value += $item->corruption;
	    }
	    $convert = false;
	    if($set_bad_value <= $max_bad_value) {
	      if ( $highest_value == null ) {
	        $convert = true;
	      } elseif ( $highest_value < $set_good_value) {
	        $convert = true;
	      } elseif ( $highest_value == $set_good_value && $highest_bad_value > $set_bad_value) {
	        $convert = true;
	      }
	    }
	    if ($convert) {
	      $highest_value = $set_good_value;
	      $highest_id = $id;
	      $highest_bad_value = $set_bad_value;
	    }
	  }
		if($highest_id === null) {
			return self::findHighestGoodValue($max_bad_value + 5, $combinations);
		}
	  return $combinations[$highest_id];
	}
}
