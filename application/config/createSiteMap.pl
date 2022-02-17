#!/usr/bin/perl 


use 5.008002;
use HTML::Entities;
use HTML::Entities qw(encode_entities_numeric);
my $LOGFILE;
$LOGFILE = $ARGV[0];
$outFile = $ARGV[1];
my $priority = "0.5";
my $frequency = "monthly";
if(@ARGV > 2) {
 $priority = $ARGV[2];
}
if(@ARGV > 3) {
 $frequency = $ARGV[3];
}
open (MYFILE, $LOGFILE);
my $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
$xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">"."\n";
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
    if($count > 25000)
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
    $fuids[0] = $line;
    my $loc .= xml('loc',join ',',@fuids);
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
    my $lastMod .= xml('lastmod',join ',',@fuids);	
    $fuids[0] = $priority;
    my $priority .= xml('priority',join ',',@fuids);
    $fuids[0] = $frequency;
    my $freq .= xml('changefreq',join ',',@fuids);
    $fuids[0] =  $loc;
    $fuids[1] =  $lastMod;
    $xml .= "\t<url>\n"; 
    $xml .= $loc ;
    $xml .= $lastMod;
    $xml .= $priority;
    $xml .= $freq;
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


