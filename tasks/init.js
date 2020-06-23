var prompt = require('prompt'),
	colors = require('colors/safe'),
	fs = require('fs');

function exit() {
	console.log(colors.inverse(colors.bold(' Goodbye \\m/(><)\\m/ ')));

	return false;
}

function writeJSON(args) {
	var acfRepo = {
		repositories: [
			{
				type: 'package',
				package: {
					name: 'advanced-custom-fields/advanced-custom-fields-pro',
					version: args.acfVersion,
					type: 'wordpress-plugin',
					dist: {
						type: 'zip',
						url:
							'https://connect.advancedcustomfields.com/index.php?t=' +
							args.acfVersion +
							'&p=pro&a=download&k=' +
							args.acfKey
					}
				}
			}
		],
		require: {
			'advanced-custom-fields/advanced-custom-fields-pro': args.acfVersion
		}
	};

	fs.writeFile(
		'./composer-merge/acf.json',
		JSON.stringify(acfRepo, null, 2),
		function(err) {
			if (err) {
				console.log(err);
				return exit();
			}

			console.log(
				colors.yellow('Your ACF configuration is complete, just run ') +
					colors.red.underline('composer install') +
					colors.yellow(' to finish setting up WoodChippr!')
			);

			return exit();
		}
	);
}

prompt.message = '';

prompt.start();

console.log(
	colors.white(
		'Welcome to the WoodChippr init script!\n\n' +
			'This script will set up some config for composer for you.\n' +
			'(At the moment that just means ACF Pro :P)\n\n'
	)
);

prompt.get(
	{
		properties: {
			acf: {
				description: colors.yellow(
					'Do you want do configure ACF Pro for this install? (y/N)'
				),
				default: 'n',
				type: 'string'
			},
			acfKey: {
				description: colors.yellow('Please enter your ACF Pro key'),
				type: 'string',
				required: true,
				ask: function() {
					return prompt.history('acf').value.toLowerCase()[0] === 'y';
				}
			},
			acfVersion: {
				description: colors.yellow('What version would you like to install?'),
				default: '5.8.12',
				type: 'string',
				ask: function() {
					return prompt.history('acfKey') !== null &&
						prompt.history('acfKey').value.length > 0
						? true
						: false;
				}
			}
		}
	},
	function(err, result) {
		if (!err) {
			if (result.acf === 'n') {
				return exit();
			} else if (result.acf.toLowerCase()[0] === 'y' && result.acfKey !== '') {
				fs.stat('./composer-merge/', function(err, stats) {
					if (err) {
						fs.mkdir('./composer-merge/', function() {
							writeJSON(result);
						});
					} else {
						writeJSON(result);
					}
				});
			} else {
				return exit();
			}
		} else {
			console.log(' ');
			return exit();
		}
	}
);
