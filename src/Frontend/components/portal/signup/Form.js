/*
 * This file is part of the Sententiaregum project.
 *
 * (c) Maximilian Bosch <maximilian.bosch.27@gmail.com>
 * (c) Ben Bieler <benjaminbieler2014@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

'use strict';

import React from 'react';
import Input from 'react-bootstrap/lib/Input';
import LoadableButtonBar from '../../app/markup/LoadableButtonBar';
import PortalActions from '../../../actions/PortalActions';
import FormComponent from '../../app/FormComponent';
import RegistrationStore from '../../../store/RegistrationStore';
import Suggestions from './Suggestions';
import Success from './Success';

/**
 * Form component for the signup page.
 *
 * @author Maximilian Bosch <maximilian.bosch.27@gmail.com>
 */
export default class Form extends FormComponent {
  /**
   * Constructor.
   *
   * @param {Array} props Component properties.
   *
   * @returns {void}
   */
  constructor(props) {
    super(props);

    const validation = {
      errors:      {},
      suggestions: [],
      submitted:   false
    };

    this.state = {
      progress: false,
      data:     {
        username: null,
        password: null,
        email:    null,
        locale:   'de'
      },
      success: false,
      validation
    };

    this.errorHandler            = this._handleErrors.bind(this);
    this.successHandler          = this._renderSuccessBox.bind(this);
    this.stateEnforcementHandler = () => {
      this.setState({ validation });
    };
  }

  /**
   * Registers the store.
   *
   * @returns {void}
   */
  componentDidMount() {
    super.componentDidMount();
    RegistrationStore.addChangeListener(this.errorHandler, 'CreateAccount.Error');
    RegistrationStore.addChangeListener(this.successHandler, 'CreateAccount.Success');
  }

  /**
   * Removes the store.
   *
   * @returns {void}
   */
  componentWillUnmount() {
    super.componentWillUnmount();
    RegistrationStore.removeChangeListener(this.errorHandler, 'CreateAccount.Error');
    RegistrationStore.removeChangeListener(this.successHandler, 'CreateAccount.Success');
  }

  /**
   * Renders the component.
   *
   * @returns {React.Element} The vDOM markup.
   */
  render() {
    const {
      username,
      password,
      email,
      button
    } = this._buildTranslationComponents();

    const {
      usernameStyle,
      passwordStyle,
      emailStyle,
      localeStyle
    } = this._getBootstrapStyles(['locale'], ['button']);

    const errors = this._renderErrors();

    return (
      <form onSubmit={this._createAccount.bind(this)} ref="form">
        <Suggestions suggestions={this.state.validation.suggestions} />
        {this.state.success ? <Success /> : null}
        <Input
          type="text"
          name="username"
          placeholder={username}
          bsStyle={usernameStyle}
          value={this.state.data.username}
          onChange={this.changeProperty.bind(this)}
          hasFeedback
          help={errors.hasOwnProperty('username') ? errors['username'] : null}
          ref="username"
          autoFocus={true}
        />
        <Input
          type="password"
          name="password"
          placeholder={password}
          value={this.state.data.password}
          bsStyle={passwordStyle}
          onChange={this.changeProperty.bind(this)}
          hasFeedback
          help={errors.hasOwnProperty('password') ? errors['password'] : null}
          ref="password"
        />
        <Input
          type="email"
          name="email"
          placeholder={email}
          value={this.state.data.email}
          bsStyle={emailStyle}
          onChange={this.changeProperty.bind(this)}
          hasFeedback
          help={errors.hasOwnProperty('email') ? errors['email'] : null}
          ref="email"
        />
        <Input
          type="select"
          name="locale"
          onChange={this.changeProperty.bind(this)}
          value={this.state.data.locale}
          bsStyle={localeStyle}
          help={errors.hasOwnProperty('locale') ? errors['locale'] : null}
          ref="locale"
        >
          <option value="de">Deutsch (Deutschland)</option>
          <option value="en">English (USA)</option>
        </Input>

        <LoadableButtonBar btnLabel={button} progress={this.state.progress} ref="button" />
      </form>
    );
  }

  /**
   * Hook to create the new account.
   *
   * @param {Object} e Event object.
   *
   * @returns {void}
   * @private
   */
  _createAccount(e) {
    e.preventDefault();
    this.setState({ progress: true });

    PortalActions.registration({
      username: this.state.data.username,
      password: this.state.data.password,
      email:    this.state.data.email,
      locale:   this.state.data.locale
    });
  }

  /**
   * Handles the errors from the registration store.
   *
   * @returns {void}
   * @private
   */
  _handleErrors() {
    this.setState({
      validation: {
        errors:      RegistrationStore.getErrors(),
        suggestions: RegistrationStore.getSuggestions(),
        submitted:   true
      },
      progress: false,
      data:     this._getErasedFormFieldPatch()
    });
  }

  /**
   * Handles a registration success.
   *
   * @returns {void}
   * @private
   */
  _renderSuccessBox() {
    this.setState({
      validation: {
        errors:      [],
        suggestions: [],
        submitted:   false
      },
      progress: false,
      success:  true,
      data:     this._getErasedFormFieldPatch()
    });
  }

  /**
   * @inheritdoc
   */
  _getFormFields() {
    return ['username', 'password', 'email', 'button'];
  }

  /**
   * @inheritdoc
   */
  _getTranslationPrefix() {
    return 'pages.portal.create_account.form';
  }

  /**
   * Getter for a clean data patch.
   *
   * @returns {Object.<string>} New patch.
   * @private
   */
  _getErasedFormFieldPatch() {
    const patch       = this.state.data;
    patch['password'] = null;

    return patch;
  }
}
