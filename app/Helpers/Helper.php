<?php

if ( ! function_exists('format_date')) {

    function format_date($date, $months = "")
    {
        if ($date != "") {
            $months = array(
                "",
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            );
            if (strpos($date, " ")) {
                $time = explode(" ", $date);
                $date = $time[0];
            }

            $dat   = explode("-", $date);
            $month = (int)$dat[1];
            $day   = $dat[2];
            $year  = $dat[0];

            return $months[$month] . " " . $day . " de " . $year;
        }

        return '';
    }
}


if ( ! function_exists('format_date_time')) {

    function format_date_time($date, $months = "")
    {
        if ($date != "") {
            $months = array(
                "",
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            );
            if (strpos($date, " ")) {
                $time = explode(" ", $date);
                $date = $time[0];
            }

            $dat   = explode("-", $date);
            $month = (int)$dat[1];
            $day   = $dat[2];
            $year  = $dat[0];

            return $months[$month] . " " . $day . " de " . $year . (isset($time[1]) ? ' ' . $time[1] : '');
        }

        return '';
    }
}


if ( ! function_exists('format_hour')) {
    function format_hour($hour)
    {
        return Date('g:i a', strtotime($hour));
    }
}

if ( ! function_exists('get_info')) {

    function get_info($name)
    {
        return \App\Models\Configsite::getInfo($name);
    }
}


if ( ! function_exists('format_url')) {

    function format_url($url)
    {
        if ($url != "") {
            return '//' . preg_replace('#^https?://#', '', $url);
        } else {
            return '#';
        }
    }
}

if ( ! function_exists('chstr')) {
    function chstr($str)
    {
        $code  = array('á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $code2 = array('a', 'e', 'i', 'o', 'u', 'n', 'A', 'E', 'I', 'O', 'U', 'N');
        $str   = str_replace($code, $code2, $str);

        $search  = '-';
        $replace = '-';

        $trans = array(
            $search                     => $replace,
            "\s+"                       => $replace,
            "[^a-z0-9" . $replace . "]" => '',
            $replace . "+"              => $replace,
            $replace . "$"              => '',
            "^" . $replace              => ''
        );

        $str = strip_tags(strtolower($str));

        foreach ($trans as $key => $val) {
            $str = preg_replace("#" . $key . "#", $val, $str);
        }

        return trim(stripslashes($str));
    }
}

if ( ! function_exists('current_option')) {
    function current_option($current, $option)
    {
        return isset($current) && $current == $option ? 'current_option' : '';
    }
}

if ( ! function_exists('ip_address')) {
    function ip_address()
    {
        if ( ! empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}


if ( ! function_exists('stlower')) {
    function stlower($string)
    {
        return ucwords(mb_strtolower($string));
    }
}


if ( ! function_exists('cast_stock')) {
    function cast_stock($stock)
    {
        return is_numeric($stock) ? (int)$stock : 0;
    }
}

if ( ! function_exists('get_number')) {
    function get_number($id)
    {
        $arr = explode('x', $id);

        return $arr[1];
    }
}
