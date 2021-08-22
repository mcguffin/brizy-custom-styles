const fs = require('fs');
const changeCase = require('change-case');
const fa_path = './node_modules/@fortawesome/fontawesome-free';
const fa_vars = fs.readFileSync(`${fa_path}/scss/_variables.scss`,{encoding:'utf-8'});
const regex = /\$fa-var-([a-z0-9-]+): \\([0-f]+);/;
const json = {}
const choices = {}
const get_sets = fa => {
	const s = [];
	if ( fs.existsSync( `${fa_path}/svgs/brands/${fa}.svg` ) ) {
		s.push('fab');
	}
	if ( fs.existsSync( `${fa_path}/svgs/regular/${fa}.svg` ) ) {
		s.push('far');
	}
	if ( fs.existsSync( `${fa_path}/svgs/solid/${fa}.svg` ) ) {
		s.push('fas');
	}
	return s;
}

let c=0;
fa_vars
	.split('\n')
	.map( el => {
		const match = el.match(regex);
		if ( match === null ) {
			return false;
		}
		const [ str, name, hex ] = match

		return json[ name ] = {
			name: changeCase.capitalCase( name ),
			sets: get_sets(name),
			hex
		};
	})
	.filter( el => el !== false );


// prefix fa, fab, far

fs.mkdirSync('./json',{recursive:true});
fs.writeFileSync('./json/fa-icons.json', JSON.stringify(json,null,2),{encoding:'utf-8'});
Object.keys(json).forEach( k => {
	json[k].sets.forEach( s => {
		choices[ `${s} fa-${k}` ] = json[k].name;
	})
});
fs.writeFileSync('./json/fa-choices.json', JSON.stringify(choices,null,2),{encoding:'utf-8'});
