'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.addColumn('Users', 'publicKey', {
      type: Sequelize.TEXT,
      allowNull: true,
    });
    await queryInterface.addColumn('Users', 'privateKey', {
      type: Sequelize.TEXT, // Sẽ được lưu dưới dạng mã hóa
      allowNull: true,
    });
  },
  down: async (queryInterface, Sequelize) => {
    await queryInterface.removeColumn('Users', 'publicKey');
    await queryInterface.removeColumn('Users', 'privateKey');
  }
};
