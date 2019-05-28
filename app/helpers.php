<?php


if (! function_exists('split_name')) {
    /**
     * Assign high numeric IDs to a config item to force appending.
     *
     * @param  string  $fullname
     * @return array
     */
    function split_name(string $fullname)
    {
        $hyphenated = array('欧阳', '太史', '端木', '上官', '司马', '东方', '独孤', '南宫', '万俟', '闻人', '夏侯', '诸葛', '尉迟', '公羊', '赫连', '澹台', '皇甫',
        '宗政', '濮阳', '公冶', '太叔', '申屠', '公孙', '慕容', '仲孙', '钟离', '长孙', '宇文', '城池', '司徒', '鲜于', '司空', '汝嫣', '闾丘', '子车', '亓官',
        '司寇', '巫马', '公西', '颛孙', '壤驷', '公良', '漆雕', '乐正', '宰父', '谷梁', '拓跋', '夹谷', '轩辕', '令狐', '段干', '百里', '呼延', '东郭', '南门',
        '羊舌', '微生', '公户', '公玉', '公仪', '梁丘', '公仲', '公上', '公门', '公山', '公坚', '左丘', '公伯', '西门', '公祖', '第五', '公乘', '贯丘', '公皙',
        '南荣', '东里', '东宫', '仲长', '子书', '子桑', '即墨', '达奚', '褚师', );
        $vLength = mb_strlen($fullname, 'utf-8');
        $lastname = '';
        $firstname = ''; //前为姓,后为名
        if ($vLength > 2) {
            $preTwoWords = mb_substr($fullname, 0, 2, 'utf-8'); //取命名的前两个字,看是否在复姓库中
            if (in_array($preTwoWords, $hyphenated)) {
                $lastname = $preTwoWords;
                $firstname = mb_substr($fullname, 2, 10, 'utf-8');
            } else {
                $lastname = mb_substr($fullname, 0, 1, 'utf-8');
                $firstname = mb_substr($fullname, 1, 10, 'utf-8');
            }
        } elseif ($vLength == 2) {
            //全名只有两个字时,以前一个为姓,后一下为名
            $lastname = mb_substr($fullname, 0, 1, 'utf-8');
            $firstname = mb_substr($fullname, 1, 10, 'utf-8');
        } else {
            $lastname = $fullname;
        }
        return [
            'lastname' => $lastname,
            'firstname' => $firstname
        ];
    }
}
