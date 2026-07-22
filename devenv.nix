{ pkgs, lib, config, inputs, ... }:

let
  pmaVersion = "5.2.3";

  # 1. Fetch & Verify phpMyAdmin using Nix's built-in SHA256 fetcher.
  # Nix downloads the zip archive and verifies the cryptographic hash at build time.
  # If the hash does not match, Nix immediately aborts execution.
  phpMyAdminSource = pkgs.fetchzip {
    url = "https://files.phpmyadmin.net/phpMyAdmin/${pmaVersion}/phpMyAdmin-${pmaVersion}-all-languages.zip";

    # SRI SHA256 Hash. Nix enforces this hash byte-for-byte.
    # (If the file is modified or tampered with on the server, Nix will refuse to build)
    hash = "sha256-qmyjvZdcH7iw3A7L0+811d491vgoMDXyPsCgiRE1VQA=";
    stripRoot = true; # Strips top-level directory inside the zip automatically
  };

  # 2. Inject config.inc.php directly inside the Nix store build
  phpMyAdminConfigured = pkgs.runCommand "phpmyadmin-configured" {} ''
    cp -r ${phpMyAdminSource} $out
    chmod -R +w $out
    cat << 'EOF' > $out/config.inc.php
<?php
$i = 0; $i++;
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['AllowNoPassword'] = true;
EOF
  '';

in {
  # https://devenv.sh/basics/
  #env.GREET = "devenv";
  #env.GREET = "Car Service App Dev Environment";
  env.GREET = "XAWAD (XAMPP Alternative Web Apps Development) Environment";

  # https://devenv.sh/packages/
  packages = with pkgs; [
    git
    php83
    php83Packages.composer

    inputs.my-nvim.packages.${pkgs.stdenv.system}.default

    curl
    unzip
  ];

  # https://devenv.sh/languages/
  # languages.rust.enable = true;
  languages.php = {
    enable = true;
    package = pkgs.php83;
    #extensions = [
    #  "mysqli" "pdo_mysql" "mbstring" "zip" "gd"
    #];
  };

  # https://devenv.sh/processes/
  # processes.dev.exec = "${lib.getExe pkgs.watchexec} -n -- ls -la";

  # https://devenv.sh/services/
  # services.postgres.enable = true;
  services.mysql = {
    enable = true;
    package = pkgs.mariadb;

    # Creates an initial database automatically on first start
    initialDatabases = [
      { name = "car_service_db"; }
    ];

    # Creates an initial database user automatically on first start
    #ensureUsers = [
    #  {
    #    name = "app_user";
    #    password = "app_password";
    #    ensurePermissions = {
    #      "car_service_db.*" = "ALL PRIVILEGES";
    #    };
    #  }
    #];

    # Initialize root user for local passwordless login
    #initialScript = ''
    #  CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY '';
    #  GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
    #  FLUSH PRIVILEGES;
    #'';

  };

  # Background Processes (App Server & phpMyAdmin Server)
  processes = {
    #enable = true;

    #phpmyadmin.exec = "php -S 127.0.0.1:8000 -t phpmyadmin";
    #
    # 3. Serve straight from the Nix Store!
    phpmyadmin = {
      start.enable = true; # If true, the process will start when running command 'devenv up'.
      exec = "php -S 127.0.0.1:8000 -t ${phpMyAdminConfigured}";
    };

    app1 = {
      start.enable = true; # Can be stop with command 'devenv process stop app1'.
      exec = "php -S 127.0.0.1:8001 -t apps/app1/public";
    };

    app2 = {
      start.enable = false; # Need to manually start it with command 'devenv process start app2'.
      exec = "php -S 127.0.0.1:8002 -t apps/app2/public";
    };

    app3 = {
      start.enable = false; # Need to manually start it with command 'devenv process start app3'.
      exec = "php -S 127.0.0.1:8003 -t apps/app3/public";
    };

    /*
    phpmyadmin.exec = ''
      PMA_DIR=".devenv/phpmyadmin"
      if [ ! -d "$PMA_DIR" ]; then
        echo "Downloading phpMyAdmin..."
        mkdir -p "$PMA_DIR"
        tar -xzf ${pkgs.phpMyAdmin}/share/phpMyAdmin/*.tar.gz -C "$PMA_DIR" --strip-components=1

        # Inject basic config to connect to local MariaDB socket/port without prompts
        cat <<'EOF' > "$PMA_DIR/config.inc.php"
<?php
$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['host'] = '127.0.0.1';
$cfg['Servers'][$i]['AllowNoPassword'] = true;
EOF
      fi
      php -S 127.0.0.1:8000 -t "$PMA_DIR"
    '';
    */

  };

  # https://devenv.sh/scripts/
  scripts.hello.exec = ''
    echo Hello from $GREET
  '';

  # https://devenv.sh/basics/
  enterShell = ''
    echo "======================================================="
    hello         # Run scripts directly
    #git --version # Use packages
    echo "======================================================="
    #echo "  1. Start MariaDB:       devenv up"
    #echo "  2. App Server (Tab 2):  php -S 127.0.0.1:8000 -t public"
    #echo "  3. phpMyAdmin (Tab 3):  php -S 127.0.0.1:8001 -t phpmyadmin"
    echo "To start database (MariaDB) and it's admin interface (phpMyAdmin): 'devenv up'"
    echo "   Then MariaDB can be access via phpMyAdmin at 'http://127.0.0.1:8000'"
    echo ""
    #echo "To start App1: php -S 127.0.0.1:8001 -t apps/app1/public"
    echo "To start App1: 'devenv processes start app1'"
    echo "   Then the App1 can be access at 'http://127.0.0.1:8001'"
    echo ""
    #echo "To start App2: php -S 127.0.0.1:8002 -t apps/app2/public"
    echo "To start App2: 'devenv processes start app2'"
    echo "   Then the App2 can be access at 'http://127.0.0.1:8002'"
    echo ""
    #echo "To start App3: php -S 127.0.0.1:8003 -t apps/app3/public"
    echo "To start App3: 'devenv processes start app3'"
    echo "   Then the App3 can be access at 'http://127.0.0.1:8003'"
    echo "======================================================="
  '';

  # https://devenv.sh/tasks/
  # tasks = {
  #   "myproj:setup".exec = "mytool build";
  #   "devenv:enterShell".after = [ "myproj:setup" ];
  # };

  # https://devenv.sh/tests/
  enterTest = ''
    echo "Running tests"
    git --version | grep --color=auto "${pkgs.git.version}"
  '';

  # https://devenv.sh/git-hooks/
  # git-hooks.hooks.shellcheck.enable = true;

  # See full reference at https://devenv.sh/reference/options/
}
