#!/usr/bin/perl 


use 5.008002;
use HTML::Entities;
use HTML::Entities qw(encode_entities_numeric);
my $LOGFILE;
my $count = @ARGV;
$LOGFILE = $ARGV[0];
$outFile = $ARGV[$count-1];
open (OUTFILE, '>'.$outFile);
my $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n";
for($i = 0; $i < $count-1; $i=$i+2) {

    ($Second, $Minute, $Hour, $Day, $Month, $Year, $WeekDay, $DayOfYear, $IsDST) =  localtime((stat($ARGV[$i]))[9]);
    $Year += 1900;
    $Month++;
    if($Month < 10) {
	$Month = "0".$Month;
    }
    if($Day < 10) {
        $Day = "0".$Day;
    }
    $date = "$Year-$Month-$Day";
    my @fuids;
    my @pers1 = split(/\//,$ARGV[$i+1]);
    my $domain = @pers1[5];
    my @pers2 = split(/\//,$ARGV[$i]);
    my $fName = @pers2[5];
    $fuids[0] = "https://".$domain.".shiksha.com/".$fName;
    my $loc .= xml('loc',join ',',@fuids);
    $fuids[0] = $date;
    my $mod .= xml('lastmod',join ',',@fuids);
    $xml .= "\t<sitemap>\n";
    $xml .= $loc;
    $xml .= $mod;
    $xml .= "\t</sitemap>\n";

}
$xml .= '</sitemapindex>';
print OUTFILE $xml;
sub xml {
    my $tag = shift;
    my $val = encode_entities_numeric(shift);
    return "\t<$tag>$val</$tag>\n";
}


