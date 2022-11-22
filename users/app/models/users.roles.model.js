module.exports = function (ConnUsers, Sequelize) {
	const UsersRoles = ConnUsers.define('users_roles', {	
	    id: {
			allowNull: false,
			type: Sequelize.INTEGER,
			autoIncrement: true,
			primaryKey: true
   	 	},
		user_id: {
			allowNull: false,
			type: Sequelize.INTEGER
		},
		role_id: {
			allowNull: false,
			type: Sequelize.INTEGER
		}
	}, {
		freezeTableName: true 
	});
	
	return UsersRoles;
}