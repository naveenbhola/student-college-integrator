#!/usr/bin/perl 


use 5.008002;
use HTML::Entities;
use HTML::Entities qw(encode_entities_numeric);
my $LOGFILE;
$LOGFILE = $ARGV[0];
$outFile = $ARGV[1];
#my $priority = "0.5";
#my $frequency = "monthly";
#if(@ARGV > 2) {
 #$priority = $ARGV[2];
#}
#if(@ARGV > 3) {
 #$frequency = $ARGV[3];
#}
open (MYFILE, $LOGFILE);
my $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:news=\"http://www.google.com/schemas/sitemap-news/0.9\">"."\n";
my $line;
my $fileNum = 1;
my $count = 0;
open (OUTFILE, '>'.$outFile.$fileNum.".xml");
foreach $line (<MYFILE>) {
#    print $line;
    chomp($line);              # remove the newline from $line.
    if($line eq "") {
        next;
    }
    $count++;
    #print $count."\n";
    if($count > 1000)
    {
    #    print "In file";
        $count = 1;
        $xml .= '</urlset>';
        print OUTFILE $xml;
        $fileNum++;
        close (OUTFILE);
    #    print "Opening File ".$outFile.$fileNum;
        open (OUTFILE, '>'.$outFile.$fileNum.".xml");
    #    print "Opening File ".$outFile.$fileNum;
        $xml = "";
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n";

    }

    my @fuids;
    my @fields = split /::::/, $line;
    my ($url, $title, $creationDate) = @fields[0,1,2];
    #$fuids[0] = $line;
    #my $loc .= xml('loc',join ',',$url);
    #my $access .= xml('news:access',join ',');	
    #my $genres .= xml('news:genres',join ',');
    ($Second, $Minute, $Hour, $Day, $Month, $Year, $WeekDay, $DayOfYear, $IsDST) = localtime(time);
    $Year += 1900;
    $Month++;
    if($Day < 10) {
        $Day = "0".$Day;
    }
    if($Month < 10) {
        $Month = "0".$Month;
    }
    $date = "$Year-$Month-$Day";
    $fuids[0] = $date;
    my $publication_date .= xml('news:publication_date',join ',',$creationDate);	
   # $fuids[0] = $priority;
  #  my $priority .= xml('news:priority',join ',',@fuids);
 #   $fuids[0] = $frequency;
#    my $freq .= xml('news:changefreq',join ',',@fuids);
    my $title .= xml('news:title',join ',',$title);
    #my $keywords .= xml('news:keywords',join ',',$keywords);
    #my $stock_tickers .= xml('news:stock_tickers',join ',',$stock_tickers);  
    #$fuids[0] =  $loc;
    #$fuids[1] =  $lastMod;
    $xml .= "\t<url>\n"; 
    $xml .= '<loc>';
    $xml .= '<![CDATA['.$url.']]>';
    $xml .= '</loc>';
    $xml .= "<news:news>";
    $xml .= "\t<news:publication>\n";
    $xml .= "<news:name>Shiksha Education News";
    $xml .= "</news:name>";
    $xml .= "<news:language>en";
    $xml .= "</news:language>";
    $xml .= "</news:publication>";
    $xml .= $publication_date;
    $xml .= $title;
    #$xml .= $freq;
    #$xml .= $titleTag;
    #$xml .= $keywords;
    #$xml .= $stock_tickers;
    $xml .= "</news:news>";
    $xml .= "\t</url>\n"; 
}
$xml .= '</urlset>';
print OUTFILE $xml;
#print $xml;
close (MYFILE);
sub xml {
    my $tag = shift;
    my $val = encode_entities_numeric(shift);
    return "\t<$tag>$val</$tag>\n";
}


