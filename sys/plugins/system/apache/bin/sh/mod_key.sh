#!/usr/bin/expect --
set filepath [lindex $argv 0]
set password [lindex $argv 1]
proc modulus {} {
    puts "VALID_FILE";
    expect eof;
    puts $expect_out(buffer);
    exit;
}
log_user 0
spawn openssl rsa -noout -modulus -in $filepath
expect {
  "Error opening Private Key" {puts "ERROR_OPENING_FILE"; exit}
  "unable to load Private Key" {puts "INVALID_FILE"; exit}
  "Modulus="                   {modulus;}
  "Enter pass phrase for $filepath:"
  {
    if {[llength $password] == 0} {
       puts "PASSWORD_REQUIRED"; exit;
    } else {
       send "$password\n";
    }
  }
}
expect {
  "unable to load Private Key" {puts "INVALID_PASSWORD"; exit}
  "Modulus="                   {modulus;}
}

