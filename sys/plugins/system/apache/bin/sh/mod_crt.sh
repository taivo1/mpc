#!/usr/bin/expect --
set filepath [lindex $argv 0]
set password [lindex $argv 1]
log_user 0
spawn openssl x509 -noout -modulus -in $filepath
expect {
  "Error opening Certificate" {puts "ERROR_OPENING_FILE"; exit}
  "unable to load certificate" {puts "INVALID_FILE"; exit}
  "Modulus="
  {
    puts "VALID_FILE";
    expect eof
    puts $expect_out(buffer);
    exit
  }
}

