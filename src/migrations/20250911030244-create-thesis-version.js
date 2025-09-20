'use strict';
/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up(queryInterface, Sequelize) {
    await queryInterface.createTable('ThesisVersions', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      thesisId: {
        type: Sequelize.INTEGER
      },
      filePath: {
        type: Sequelize.STRING
      },
      versionName: {
        type: Sequelize.STRING
      },
      fileHash: {
        type: Sequelize.STRING
      },
      submissionDate: {
        type: Sequelize.DATE
      },
      createdAt: {
        allowNull: false,
        type: Sequelize.DATE
      },
      updatedAt: {
        allowNull: false,
        type: Sequelize.DATE
      }
    });
  },
  async down(queryInterface, Sequelize) {
    await queryInterface.dropTable('ThesisVersions');
  }
};